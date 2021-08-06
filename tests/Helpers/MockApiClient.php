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

use Cloudinary\Api\ApiClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;

/**
 * Class MockApiClient
 */
class MockApiClient extends ApiClient
{
    /**
     * @var MockHandler
     */
    public $mockHandler;

    private $requestOptions;

    /**
     * MockApiClient constructor.
     *
     * @param mixed $configuration
     */
    public function __construct($configuration = null)
    {
        parent::__construct($configuration);

        $this->mockHandler = new MockHandler(
            [
                new Response(200, [], '[]'),
            ]
        );

        $config = $this->httpClient->getConfig();
        $config['handler'] = HandlerStack::create($this->mockHandler);

        $this->httpClient = new Client($config);
    }

    /**
     * @param string       $method
     * @param array|string $endPoint
     * @param array        $options
     *
     * @return PromiseInterface
     */
    protected function callAsync($method, $endPoint, $options)
    {
        $this->requestOptions = $options;

        return parent::callAsync($method, $endPoint, $options);
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
}
