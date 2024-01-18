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
use Cloudinary\Configuration\ApiConfig;
use Cloudinary\JsonUtils;
use Cloudinary\Log\LoggerTrait;
use Cloudinary\StringUtils;
use Cloudinary\Utils;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Promise\Create;
use GuzzleHttp\Promise\PromiseInterface;
use InvalidArgumentException;
use JsonSerializable;
use Psr\Http\Message\ResponseInterface;

/**
 * Class BaseApiClient
 *
 * @package Cloudinary\Api
 */
class BaseApiClient
{
    use LoggerTrait;

    /**
     * @var array Cloudinary API Error Classes mapping between http error codes and Cloudinary exceptions
     */
    const CLOUDINARY_API_ERROR_CLASSES
        = [
            HttpStatusCode::BAD_REQUEST           => BadRequest::class,
            HttpStatusCode::UNAUTHORIZED          => AuthorizationRequired::class,
            HttpStatusCode::FORBIDDEN             => NotAllowed::class,
            HttpStatusCode::NOT_FOUND             => NotFound::class,
            HttpStatusCode::CONFLICT              => AlreadyExists::class,
            HttpStatusCode::ENHANCE_YOUR_CALM     => RateLimited::class, // RFC6585::TOO_MANY_REQUESTS
            HttpStatusCode::INTERNAL_SERVER_ERROR => GeneralError::class,
        ];

    /**
     * @var Client The Http client instance. Performs actual network calls.
     */
    public $httpClient;

    /**
     * @var ApiConfig $api The API configuration.
     */
    protected $api;

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
        return $this->callAsync(HttpMethod::GET, $endPoint, ['query' => Utils::buildHttpQuery($queryParams)]);
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
     * Performs an HTTP POST request with the given JSON object.
     *
     * @param string|array $endPoint The API endpoint path.
     * @param              $parameters
     *
     * @return ApiResponse
     *
     * @internal
     */
    public function postJson($endPoint, $parameters = [])
    {
        return $this->postJsonAsync($endPoint, $parameters)->wait();
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
        return $this->callAsync(HttpMethod::DELETE, $endPoint, ['form_params' => $fields])->wait();
    }

    /**
     * Performs an HTTP DELETE request with the given params.
     *
     * @param string|array           $endPoint The API endpoint path.
     * @param JsonSerializable|array $json     JSON data.
     *
     * @return ApiResponse
     *
     * @internal
     */
    public function deleteJson($endPoint, $json = [])
    {
        return $this->callAsync(HttpMethod::DELETE, $endPoint, ['json' => $json])->wait();
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
     * Performs an HTTP PUT request with the given form params
     *
     * @param string|array $endPoint The API endpoint path.
     * @param array        $fields   Fields to send.
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * @internal
     */
    public function put($endPoint, $fields)
    {
        return $this->callAsync(HttpMethod::PUT, $endPoint, ['form_params' => $fields])->wait();
    }

    /**
     * Performs an HTTP PUT request with the given form params.
     *
     * @param string|array           $endPoint The API endpoint path.
     * @param JsonSerializable|array $json     JSON data.
     *
     * @return ApiResponse
     */
    public function putJson($endPoint, $json)
    {
        return $this->callAsync(HttpMethod::PUT, $endPoint, ['json' => $json])->wait();
    }

    /**
     * Gets the API version string from the version.
     *
     * @param string $apiVersion The API version in the form Major.minor (for example: 1.1).
     *
     * @return string API version string
     *
     * @internal
     */
    public static function apiVersion($apiVersion = ApiConfig::DEFAULT_API_VERSION)
    {
        return 'v' . str_replace('.', '_', $apiVersion);
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
        $endPoint           = self::finalizeEndPoint($endPoint);
        $options['headers'] = ArrayUtils::mergeNonEmpty(
            ArrayUtils::get($options, 'headers', []),
            ArrayUtils::get($options, 'extra_headers', [])
        );
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
                        return Create::promiseFor($this->handleApiResponse($response));
                    } catch (Exception $e) {
                        $this->getLogger()->critical(
                            'Async request failed',
                            [
                                'code'    => $e->getCode(),
                                'message' => $e->getMessage(),
                            ]
                        );

                        return Create::rejectionFor($e);
                    }
                },
                function (Exception $error) {
                    $this->getLogger()->critical(
                        'Async request failed',
                        [
                            'code'    => $error->getCode(),
                            'message' => $error->getMessage(),
                        ]
                    );
                    if ($error instanceof ClientException) {
                        return Create::rejectionFor($this->handleApiResponse($error->getResponse()));
                    }

                    return Create::rejectionFor($error);
                }
            );
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

        if ($statusCode !== HttpStatusCode::OK) {
            if (array_key_exists($statusCode, self::CLOUDINARY_API_ERROR_CLASSES)) {
                $errorClass   = self::CLOUDINARY_API_ERROR_CLASSES[$statusCode];
                $responseJson = $this->parseJsonResponse($response);
                $message      = ArrayUtils::get(
                    $responseJson['error']['message'],
                    'message',
                    $responseJson['error']['message']
                );
                $this->getLogger()->critical(
                    'Request to Cloudinary server returned an error',
                    [
                        'statusCode' => $statusCode,
                        'message'    => $message,

                    ]
                );
                throw new $errorClass($message);
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
            $message = sprintf(
                'Error parsing server response (%s) - %s. Got - %s',
                $response->getStatusCode(),
                $response->getBody(),
                $iae
            );
            $this->getLogger()->error($message);
            throw new GeneralError($message);
        }

        return $responseJson;
    }
}
