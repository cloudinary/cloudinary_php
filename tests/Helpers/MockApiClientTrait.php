<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;

/**
 * Trait MockUploadApiClientTrait
 *
 * @package Cloudinary\Test\Traits
 */
trait MockApiClientTrait
{
    /**
     * @var MockHandler $mockHandler
     */
    public $mockHandler;

    /**
     * @var array $requestOptions
     */
    private $requestOptions;

    protected function createHttpClient(): void
    {
        $this->mockHandler = new MockHandler(
            [
                new Response(200, [], '[]'),
            ]
        );

        $config            = $this->buildHttpClientConfig();
        $config['handler'] = HandlerStack::create($this->mockHandler);

        $this->httpClient = new Client($config);
    }

    /**
     * Performs an HTTP call asynchronously.
     *
     * @param string       $method   An HTTP method.
     * @param array|string $endPoint An API endpoint path.
     * @param array        $options  An array containing request body and additional options passed to the HTTP Client.
     *
     * @return PromiseInterface
     *
     * @internal
     */
    protected function callAsync(string $method, array|string $endPoint, array $options): PromiseInterface
    {
        $this->requestOptions = $options;

        return parent::callAsync($method, $endPoint, $options);
    }

    /**
     * Returns request options.
     *
     * @return array
     */
    public function getRequestOptions()
    {
        return $this->requestOptions;
    }

    /**
     * Returns request multipart options.
     *
     * @return array
     */
    public function getRequestMultipartOptions()
    {
        if (empty($this->requestOptions['multipart'])) {
            return [];
        }

        return array_reduce(
            $this->requestOptions['multipart'],
            static function ($options, $item) {
                $options[$item['name']] = $item['contents'] instanceof Stream
                    ? $item['contents']->getContents()
                    : $item['contents'];

                return $options;
            }
        );
    }

    /**
     * @return \string[][]
     */
    public function getLastRequestHeaders()
    {
        return $this->mockHandler->getLastRequest()->getHeaders();
    }
}
