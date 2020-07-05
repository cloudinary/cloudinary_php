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
use Cloudinary\Configuration\AccountConfig;
use Cloudinary\Configuration\ApiConfig;
use Cloudinary\Configuration\Configuration;
use Cloudinary\FileUtils;
use Cloudinary\Utils;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\LimitStream;
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
     * @var AccountConfig $account The account configuration.
     */
    protected $account;

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
     * Signs posted parameters using configured account credentials and posts to the endpoint.
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
                        'code'    => $e->getCode(),
                        'message' => $e->getMessage(),
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
