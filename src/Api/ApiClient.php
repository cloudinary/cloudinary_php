<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Api;

use Cloudinary\Api\Exception\AlreadyExists;
use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Exception\AuthorizationRequired;
use Cloudinary\Api\Exception\BadRequest;
use Cloudinary\Api\Exception\GeneralError;
use Cloudinary\Api\Exception\NotAllowed;
use Cloudinary\Api\Exception\NotFound;
use Cloudinary\Api\Exception\RateLimited;
use Cloudinary\ArrayUtils;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\AccountConfig;
use Cloudinary\Configuration\ApiConfig;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Configuration\LoggingConfig;
use Cloudinary\FileUtils;
use Cloudinary\JsonUtils;
use Cloudinary\Log\LoggerTrait;
use Cloudinary\StringUtils;
use Cloudinary\Utils;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\LimitStream;
use InvalidArgumentException;
use JsonSerializable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Teapot\StatusCode;
use Teapot\StatusCode\Vendor\Twitter;

/**
 * Class ApiClient
 *
 * @internal
 */
class ApiClient
{
    use LoggerTrait;

    /**
     * @var string Cloudinary API version
     */
    const API_VERSION = '1.1';

    /**
     * @var array Cloudinary API Error Classes mapping between http error codes and Cloudinary exceptions
     */
    const CLOUDINARY_API_ERROR_CLASSES = [
        StatusCode::BAD_REQUEST           => BadRequest::class,
        StatusCode::UNAUTHORIZED          => AuthorizationRequired::class,
        StatusCode::FORBIDDEN             => NotAllowed::class,
        StatusCode::NOT_FOUND             => NotFound::class,
        StatusCode::CONFLICT              => AlreadyExists::class,
        Twitter::ENHANCE_YOUR_CALM        => RateLimited::class, // RFC6585::TOO_MANY_REQUESTS
        StatusCode::INTERNAL_SERVER_ERROR => GeneralError::class,
    ];

    /**
     * @var AccountConfig $account The account configuration.
     */
    protected $account;

    /**
     * @var ApiConfig $api The API configuration.
     */
    protected $api;

    /**
     * @var Client The Http client instance. Performs actual network calls.
     */
    protected $httpClient;

    /**
     * @var string Base API URI. Stored here to allow sharing it publicly (for example upload form tag)
     */
    protected $baseUri;

    /**
     * Contains information about SDK user agent. Passed to the Cloudinary servers.
     *
     * Initialized on the first call to {@see self::userAgent()}
     *
     * Sample value: CloudinaryPHP/2.3.4 (PHP 5.6.7)
     *
     * @internal
     *
     * Do not change this value
     */
    private static $userAgent = 'CloudinaryPHP/' . Cloudinary::VERSION . ' (PHP ' . PHP_VERSION . ')';

    /**
     * Additional information to be passed with the USER_AGENT, e.g. 'CloudinaryMagento/1.0.1'.
     * This value is set in platform-specific
     * implementations that use cloudinary_php.
     *
     * The format of the value should be <ProductName>/Version[ (comment)].
     *
     * @see http://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html#sec14.43
     *
     * @internal
     *
     * <b>Do not set this value in application code!</b>
     *
     * @var string
     */
    public static $userPlatform = '';

    /**
     * ApiClient constructor.
     *
     * @param $configuration
     */
    public function __construct($configuration = null)
    {
        if ($configuration === null) {
            $configuration = Configuration::instance(); // get global instance
        }

        $this->configuration($configuration);

        $this->baseUri = "{$this->api->uploadPrefix}/" . self::apiVersion() . "/{$this->account->cloudName}/";

        $clientConfig = [
            'auth'            => [$this->account->apiKey, $this->account->apiSecret],
            'base_uri'        => $this->baseUri,
            'connect_timeout' => $this->api->connectionTimeout,
            'timeout'         => $this->api->timeout,
            'proxy'           => $this->api->apiProxy,
            'headers'         => ['User-Agent' => self::userAgent()],
            'http_errors'     => false, // We handle HTTP errors by ourselves and throw corresponding exceptions
        ];

        $this->httpClient = new Client($clientConfig);
    }

    /**
     * Gets base API url.
     *
     * @return string
     *
     * @internal
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * Gets account configuration of the current client.
     *
     * @return AccountConfig
     *
     * @internal
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets Configuration.
     *
     * @param mixed $configuration The configuration source.
     *
     * @return static
     *
     * @internal
     */
    public function configuration($configuration)
    {
        $tempConfiguration = new Configuration($configuration); // TODO: improve performance here

        $tempConfiguration->account->assertNotEmpty(['cloudName', 'apiKey', 'apiSecret']);

        $this->account = $tempConfiguration->account;
        $this->api     = $tempConfiguration->api;
        $this->logging = $tempConfiguration->logging;

        return $this;
    }


    /**
     * Performs an HTTP GET request with the given query parameters.
     *
     * @param string|array $endPoint    The API endpoint path.
     * @param array        $queryParams Query parameters.
     *
     * @return ApiResponse
     *
     * @internal
     */
    public function get($endPoint, $queryParams = [])
    {
        return $this->getAsync($endPoint, $queryParams)->wait();
    }

    /**
     * Performs an HTTP GET request with the given query parameters asynchronously.
     *
     * @param string|array $endPoint    The API endpoint path.
     * @param array        $queryParams Query parameters
     *
     * @return PromiseInterface
     *
     * @internal
     */
    public function getAsync($endPoint, $queryParams = [])
    {
        return $this->callAsync(HttpMethod::GET, $endPoint, ['query' => $queryParams]);
    }

    /**
     * Performs an HTTP POST request with the given parameters.
     *
     * @param string|array $endPoint The API endpoint path.
     * @param              $parameters
     *
     * @return ApiResponse
     *
     * @internal
     */
    public function post($endPoint, $parameters = [])
    {
        return $this->postAsync($endPoint, $parameters)->wait();
    }

    /**
     * Performs an HTTP POST request with the given parameters asynchronously.
     *
     * @param string|array $endPoint The API endpoint path.
     * @param              $options
     *
     * @return PromiseInterface
     *
     * @internal
     */
    public function postAsync($endPoint, $options = [])
    {
        return $this->callAsync(HttpMethod::POST, $endPoint, $options);
    }

    /**
     * Performs an HTTP POST request with the given form parameters.
     *
     * @param string|array $endPoint   The API endpoint path.
     * @param array        $formParams The form parameters
     *
     * @return ApiResponse
     *
     * @internal
     */
    public function postForm($endPoint, $formParams)
    {
        return $this->postFormAsync($endPoint, $formParams)->wait();
    }

    /**
     * Performs an HTTP POST request with the given form parameters asynchronously.
     *
     * @param string|array $endPoint   The API endpoint path.
     * @param array        $formParams The form parameters
     *
     * @return PromiseInterface
     *
     * @internal
     */
    public function postFormAsync($endPoint, $formParams)
    {
        return $this->callAsync(HttpMethod::POST, $endPoint, ['form_params' => $formParams]);
    }

    /**
     * Sings posted parameters using configured account credentials and posts to the endpoint.
     *
     * @param string|array $endPoint   The API endpoint path.
     * @param array        $formParams The form parameters
     *
     * @return PromiseInterface
     *
     * @internal
     */
    public function postAndSignFormAsync($endPoint, $formParams)
    {
        ApiUtils::signRequest($formParams, $this->account);

        return $this->postFormAsync($endPoint, $formParams);
    }

    /**
     * Performs an HTTP POST request with the given JSON object asynchronously.
     *
     * @param string|array           $endPoint The API endpoint path.
     * @param JsonSerializable|array $json     The json object
     *
     * @return PromiseInterface
     *
     * @internal
     *
     */
    public function postJsonAsync($endPoint, $json)
    {
        return $this->callAsync(HttpMethod::POST, $endPoint, ['json' => $json]);
    }

    /**
     * Helper method for posting multipart data asynchronously.
     *
     * @param string|array $endPoint The API endpoint path.
     * @param              $multiPart
     * @param array        $headers
     * @param array        $options  Additional options for Http client
     *
     * @return PromiseInterface
     *
     * @internal
     */
    public function postMultiPartAsync($endPoint, $multiPart, $headers = null, $options = [])
    {
        ArrayUtils::addNonEmpty($options, 'multipart', $multiPart);
        ArrayUtils::addNonEmpty($options, 'headers', $headers);

        return $this->postAsync(self::finalizeEndPoint($endPoint), $options);
    }

    /**
     * Helper method for posting multipart data.
     *
     * @param string|array $endPoint The API endpoint path.
     * @param              $multiPart
     * @param array        $headers
     * @param array        $options  Additional options for Http client
     *
     * @return ApiResponse
     *
     * @internal
     *
     */
    public function postMultiPart($endPoint, $multiPart, $headers = null, $options = [])
    {
        return $this->postMultiPartAsync($endPoint, $multiPart, $headers, $options)->wait();
    }

    /**
     * Uploads a file to the Cloudinary server.
     *
     * @param string|array $endPoint   The API endpoint path.
     * @param mixed        $file       File to upload, can be a local path, URL, stream, etc.
     * @param array        $parameters Additional parameters to be sent in the body
     * @param array        $options    Additional options, including options for the HTTP client
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * @internal
     */
    public function postFile($endPoint, $file, $parameters, $options = [])
    {
        return $this->postFileAsync($endPoint, $file, $parameters, $options)->wait();
    }

    /**
     * Uploads a file to the Cloudinary server asynchronously.
     *
     * @param string|array $endPoint   The API endpoint path.
     * @param mixed        $file       File to upload, can be a local path, URL, stream, etc.
     * @param array        $parameters Additional parameters to be sent in the body
     * @param array        $options    Additional options, including options for the HTTP client
     *
     * @return PromiseInterface
     *
     * @throws ApiError
     * @throws Exception
     *
     * @internal
     */
    public function postFileAsync($endPoint, $file, $parameters, $options = [])
    {
        $unsigned = ArrayUtils::get($options, 'unsigned');

        if (! $unsigned) {
            ApiUtils::signRequest($parameters, $this->account);
        }

        $fileHandle = FileUtils::handleFile($file);

        if ($fileHandle instanceof UriInterface) {
            return $this->postSingleChunkAsync($endPoint, $fileHandle, $parameters, $options);
        }

        if (empty(ArrayUtils::get($options, 'filename')) && FileUtils::isLocalFilePath($file)) {
            $options['filename'] = basename($file); // set local file name
        }

        $size = $fileHandle->getSize();

        $options[ApiConfig::CHUNK_SIZE] = min(
            $this->api->chunkSize,
            ArrayUtils::get($options, ApiConfig::CHUNK_SIZE, ApiConfig::DEFAULT_CHUNK_SIZE)
        );

        $options[ApiConfig::TIMEOUT] = ArrayUtils::get($options, ApiConfig::TIMEOUT, $this->api->uploadTimeout);

        /** @noinspection IsEmptyFunctionUsageInspection */
        if (empty($size) || $size <= $options[ApiConfig::CHUNK_SIZE]) {
            return $this->postSingleChunkAsync($endPoint, $fileHandle, $parameters, $options);
        }

        return $this->postLargeFileAsync($endPoint, $fileHandle, $parameters, $options);
    }

    /**
     * Posts a large file in chunks asynchronously
     *
     * @param string|array    $endPoint   The API endpoint path.
     * @param StreamInterface $fileHandle The file handle
     * @param array           $parameters Additional form parameters
     * @param array           $options    Additional options
     *
     * @return PromiseInterface
     * @throws Exception
     *
     * @internal
     */
    private function postLargeFileAsync($endPoint, $fileHandle, $parameters, $options = [])
    {
        $this->getLogger()->debug('Making a Large File Async POST request');

        $uploadResult = null;

        $chunkSize = ArrayUtils::get($options, ApiConfig::CHUNK_SIZE, ApiConfig::DEFAULT_CHUNK_SIZE);

        $fileSize = $fileHandle->getSize(); // Can be null as well
        /** @noinspection IsEmptyFunctionUsageInspection */
        $rangeHeaderTemplate = 'bytes %s-%s' . (! empty($fileSize) ? "/$fileSize" : '');

        $options['headers'] = [
            'X-Unique-Upload-Id' => Utils::randomPublicId($this->account->apiSecret),
        ];

        if ($fileHandle->isSeekable()) {
            $fileHandle->rewind();
        }

        while (! $fileHandle->eof()) {
            $currPos                             = $fileHandle->tell();
            $currChunk                           = new LimitStream($fileHandle, $chunkSize, $currPos);
            $options['headers']['Content-Range'] = sprintf(
                $rangeHeaderTemplate,
                $currPos,
                $currPos + $currChunk->getSize() - 1
            );

            try {
                $this->getLogger()->debug('Making a Single Chunk Async POST request');
                // TODO: use pool here or send requests in an async manner(?)
                $uploadResult = $this->postSingleChunkAsync($endPoint, $currChunk, $parameters, $options)->wait();
            } catch (Exception $e) { // TODO: retry (?)
                $this->getLogger()->critical(
                    'Single Chunk Async POST request failed',
                    [
                        'code' => $e->getCode(),
                        'message' => $e->getMessage()
                    ]
                );
                return Promise\rejection_for($e);
            }

            ArrayUtils::addNonEmptyFromOther($parameters, 'public_id', $uploadResult);
        }

        return Promise\promise_for($uploadResult);
    }

    /**
     * Posts a single chunk of the large file upload request asynchronously
     *
     * @param string|array $endPoint    The API endpoint path.
     * @param mixed        $singleChunk The data of a single chunk of the file
     * @param array        $parameters  Additional form parameters
     * @param array        $options     Additional options
     *
     * @return PromiseInterface
     *
     * @internal
     *
     */
    protected function postSingleChunkAsync($endPoint, $singleChunk, $parameters, $options = [])
    {
        $filePart = [
            'name'     => 'file',
            'contents' => $singleChunk,
        ];

        ArrayUtils::addNonEmptyFromOther($filePart, 'filename', $options);

        $multiPart    = self::buildMultiPart($parameters);
        $multiPart [] = $filePart;

        $headers = ArrayUtils::pop($options, 'headers');

        return $this->postMultiPartAsync($endPoint, $multiPart, $headers, $options);
    }

    /**
     * Performs an HTTP DELETE request with the given params
     *
     * @param string|array $endPoint The API endpoint path.
     * @param array        $fields   Fields to send
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * @internal
     */
    public function delete($endPoint, $fields = [])
    {
        $endPoint = self::finalizeEndPoint($endPoint);
        $this->getLogger()->debug('Making DELETE request', ['method' => 'DELETE', 'endPoint' => $endPoint]);
        return $this->handleApiResponse(
            $this->httpClient->delete($endPoint, ['form_params' => $fields])
        );
    }

    /**
     * Performs an HTTP PUT request with the given form params
     *
     * @param string|array $endPoint The API endpoint path.
     * @param array        $fields   Fields to send
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * @internal
     */
    public function put($endPoint, $fields)
    {
        $endPoint = self::finalizeEndPoint($endPoint);
        $this->getLogger()->debug('Making PUT request', ['method' => 'PUT', 'endPoint' => $endPoint]);
        return $this->handleApiResponse(
            $this->httpClient->put($endPoint, ['form_params' => $fields])
        );
    }

    /**
     * Performs an HTTP call asynchronously
     *
     * @param string       $method   HTTP method
     * @param string|array $endPoint The API endpoint path.
     * @param array        $options  Array containing request body and additional options passed to the HTTP Client
     *
     * @return PromiseInterface
     *
     * @internal
     */
    protected function callAsync($method, $endPoint, $options)
    {
        $endPoint = self::finalizeEndPoint($endPoint);
        $this->getLogger()->debug("Making async $method request", ['method' => $method, 'endPoint' => $endPoint]);
        return $this
            ->httpClient
            ->requestAsync($method, $endPoint, $options)
            ->then(
                function (ResponseInterface $response) {
                    $this->getLogger()->debug(
                        'Response received for async request',
                        ['statusCode' => $response->getStatusCode()]
                    );
                    try {
                        return Promise\promise_for($this->handleApiResponse($response));
                    } catch (Exception $e) {
                        $this->getLogger()->critical(
                            'Async request failed',
                            [
                                'code' => $e->getCode(),
                                'message' => $e->getMessage()
                            ]
                        );
                        return Promise\rejection_for($e);
                    }
                },
                function (Exception $error) {
                    $this->getLogger()->critical(
                        'Async request failed',
                        [
                            'code' => $error->getCode(),
                            'message' => $error->getMessage()
                        ]
                    );
                    if ($error instanceof ClientException) {
                        return Promise\rejection_for($this->handleApiResponse($error->getResponse()));
                    }

                    return Promise\rejection_for($error);
                }
            );
    }

    /**
     * Internal helper method for converting array of URL path parts to string
     *
     * @param array|string $endPoint The API endpoint path.
     *
     * @return string resulting URL path
     *
     * @internal
     */
    protected static function finalizeEndPoint($endPoint)
    {
        if (is_array($endPoint)) {
            $endPoint = ArrayUtils::implodeUrl($endPoint);
        }

        return $endPoint;
    }

    /**
     * Provides the {@see ApiClient::$userAgent} string that is passed to the Cloudinary servers.
     *
     * Prepends {@see ApiClient::$userPlatform} if it is defined.
     *
     * @return string
     *
     * @internal
     */
    protected static function userAgent()
    {
        if (empty(self::$userPlatform)) {
            return self::$userAgent;
        }

        return self::$userPlatform . ' ' . self::$userAgent;
    }

    /**
     * Gets the API version string from the version.
     *
     * @return string API version string
     *
     * @internal
     */
    protected static function apiVersion()
    {
        return 'v' . str_replace('.', '_', self::API_VERSION);
    }

    /**
     * Handles HTTP response from Cloudinary API.
     *
     * @param ResponseInterface $response Response from HTTP request to the Cloudinary server
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * @internal
     */
    private function handleApiResponse($response)
    {
        $statusCode = $response->getStatusCode();

        if ($statusCode !== StatusCode::OK) {
            if (array_key_exists($statusCode, self::CLOUDINARY_API_ERROR_CLASSES)) {
                $errorClass   = self::CLOUDINARY_API_ERROR_CLASSES[$statusCode];
                $responseJson = $this->parseJsonResponse($response);
                $this->getLogger()->critical(
                    'Request to Cloudinary server returned an error',
                    [
                        'statusCode' => $statusCode,
                        'message' => $responseJson['error']['message']
                    ]
                );
                throw new $errorClass($responseJson['error']['message']);
            }
            $message = "Server returned unexpected status code - {$response->getStatusCode()} - " .
                StringUtils::truncateMiddle($response->getBody());
            $this->getLogger()->critical($message);
            throw new GeneralError($message);
        }

        $responseJson    = $this->parseJsonResponse($response);
        $responseHeaders = $response->getHeaders();

        return new ApiResponse($responseJson, $responseHeaders);
    }

    /**
     * Parses JSON body (if possible)
     *
     * @param ResponseInterface $response Response from HTTP request to Cloudinary server
     *
     * @return mixed
     *
     * @throws GeneralError
     *
     * @internal
     */
    private function parseJsonResponse($response)
    {
        try {
            $responseJson = JsonUtils::decode($response->getBody(), true);
        } catch (InvalidArgumentException $iae) {
            $message = "Error parsing server response ({$response->getStatusCode()}) - {$response->getBody()}." .
                " Got - {$iae}";
            $this->getLogger()->error($message);
            throw new GeneralError($message);
        }

        return $responseJson;
    }

    /**
     * Builds multipart request body from an array of parameters
     *
     * @param array $parameters The input parameters
     *
     * @return array
     *
     * @internal
     */
    private static function buildMultiPart($parameters)
    {
        return array_values(
            ArrayUtils::mapAssoc(
                static function ($key, $value) {
                    return ['name' => $key, 'contents' => $value];
                },
                $parameters
            )
        );
    }
}
