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

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Exception\GeneralError;
use Cloudinary\ArrayUtils;
use Cloudinary\Configuration\ApiConfig;
use Cloudinary\Configuration\CloudConfig;
use Cloudinary\Configuration\Configuration;
use Cloudinary\FileUtils;
use Cloudinary\Utils;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\LimitStream;
use InvalidArgumentException;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class ApiClient
 *
 * @internal
 */
class ApiClient extends BaseApiClient
{
    /**
     * @var CloudConfig $cloud The cloud configuration.
     */
    protected $cloud;

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

        $this->baseUri = "{$this->api->uploadPrefix}/" . self::apiVersion($this->api->apiVersion)
                         . "/{$this->cloud->cloudName}/";

        $this->createHttpClient();
    }

    protected function createHttpClient()
    {
        $this->httpClient = new Client($this->buildHttpClientConfig());
    }

    /**
     * Gets cloud configuration of the current client.
     *
     * @return CloudConfig
     *
     * @internal
     */
    public function getCloud()
    {
        return $this->cloud;
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

        $this->cloud   = $tempConfiguration->cloud;
        $this->api     = $tempConfiguration->api;
        $this->logging = $tempConfiguration->logging;

        return $this;
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
     * Please note that form parameters are encoded in a slightly different way, see Utils::buildHttpQuery for details.
     *
     * @param string|array $endPoint   The API endpoint path.
     * @param array        $formParams The form parameters
     *
     * @return PromiseInterface
     *
     * @see Utils::buildHttpQuery
     *
     * @internal
     */
    public function postFormAsync($endPoint, $formParams)
    {
        return $this->callAsync(HttpMethod::POST, $endPoint, ['body' => Utils::buildHttpQuery($formParams)]);
    }

    /**
     * Signs posted parameters using configured cloud credentials and posts to the endpoint.
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
        if (! $this->cloud->oauthToken) {
            ApiUtils::signRequest($formParams, $this->cloud);
        }

        return $this->postFormAsync($endPoint, $formParams);
    }

    /**
     * Signs posted parameters using configured account credentials and posts as a JSON to the endpoint.
     *
     * @param string|array $endPoint The API endpoint path.
     * @param array        $params   The parameters
     *
     * @return PromiseInterface
     *
     * @internal
     */
    public function postAndSignJsonAsync($endPoint, $params)
    {
        ApiUtils::signRequest($params, $this->cloud);

        return $this->postJsonAsync($endPoint, $params);
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

        if (! $this->cloud->oauthToken && ! $unsigned) {
            ApiUtils::signRequest($parameters, $this->cloud);
        }

        try {
            $fileHandle = FileUtils::handleFile($file);
        } catch (GeneralError $e) {
            $this->getLogger()->critical(
                'Error while attempting to upload a file',
                [
                    'exception' => $e->getMessage(),
                    'class'     => self::class,
                    'endPoint'  => $endPoint,
                    'file'      => $file,
                    'options'   => $options,
                ]
            );
            throw $e;
        }

        if ($fileHandle instanceof UriInterface) {
            return $this->postSingleChunkAsync($endPoint, $fileHandle, $parameters, $options);
        }

        if (empty(ArrayUtils::get($options, 'filename')) && FileUtils::isLocalFilePath($file)) {
            $options['filename'] = basename($file); // set local file name
        }

        $size = $fileHandle->getSize();

        $options[ApiConfig::CHUNK_SIZE] = ArrayUtils::get($options, ApiConfig::CHUNK_SIZE, $this->api->chunkSize);

        $options[ApiConfig::TIMEOUT] = ArrayUtils::get($options, ApiConfig::TIMEOUT, $this->api->uploadTimeout);

        /** @noinspection IsEmptyFunctionUsageInspection */
        if (empty($size) || $size <= $options[ApiConfig::CHUNK_SIZE]) {
            return $this->postSingleChunkAsync($endPoint, $fileHandle, $parameters, $options);
        }

        return $this->postLargeFileAsync($endPoint, $fileHandle, $parameters, $options);
    }

    /**
     * Performs an HTTP call asynchronously.
     *
     * @param string       $method   An HTTP method.
     * @param string|array $endPoint An API endpoint path.
     * @param array        $options  An array containing request body and additional options passed to the HTTP Client.
     *
     * @return PromiseInterface
     *
     * @internal
     */
    protected function callAsync($method, $endPoint, $options)
    {
        static::validateAuthorization($this->cloud, $options);

        return parent::callAsync($method, $endPoint, $options);
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
            'X-Unique-Upload-Id' => Utils::randomPublicId($this->cloud->apiSecret),
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
                        'code'    => $e->getCode(),
                        'message' => $e->getMessage(),
                    ]
                );

                return Create::rejectionFor($e);
            }

            ArrayUtils::addNonEmptyFromOther($parameters, 'public_id', $uploadResult);
        }

        return Create::promiseFor($uploadResult);
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
            'name'     => ArrayUtils::get($options, 'file_field', 'file'),
            'contents' => $singleChunk,
        ];

        ArrayUtils::addNonEmptyFromOther($filePart, 'filename', $options);

        $multiPart    = self::buildMultiPart($parameters);
        $multiPart [] = $filePart;

        $headers = ArrayUtils::pop($options, 'headers');

        return $this->postMultiPartAsync($endPoint, $multiPart, $headers, $options);
    }

    /**
     * Build configuration used by HTTP client
     *
     * @return array
     *
     * @internal
     */
    protected function buildHttpClientConfig()
    {
        $clientConfig = [
            'base_uri'        => $this->baseUri,
            'connect_timeout' => $this->api->connectionTimeout,
            'timeout'         => $this->api->timeout,
            'proxy'           => $this->api->apiProxy,
            'headers'         => ['User-Agent' => self::userAgent()],
            'http_errors'     => false, // We handle HTTP errors by ourselves and throw corresponding exceptions
        ];

        if (isset($this->cloud->oauthToken)) {
            $authConfig = [
                'headers' => ['Authorization' => 'Bearer ' . $this->cloud->oauthToken],
            ];
        } else {
            $authConfig = [
                'auth' => [$this->cloud->apiKey, $this->cloud->apiSecret],
            ];
        }

        return array_merge_recursive($clientConfig, $authConfig);
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

    /**
     * Validates if all required authorization params are passed.
     *
     * @param CloudConfig $cloudConfig A config to validate.
     * @param array       $options     An array containing request body and additional options passed to the HTTP
     *                                 Client.
     *
     * @throws InvalidArgumentException In a case not all required keys are set.
     *
     * @internal
     */
    protected static function validateAuthorization($cloudConfig, $options)
    {
        $keysToValidate = ['cloudName'];

        if (empty($cloudConfig->oauthToken)) {
            array_push($keysToValidate, 'apiKey', 'apiSecret');
        }

        $cloudConfig->assertNotEmpty($keysToValidate);
    }
}
