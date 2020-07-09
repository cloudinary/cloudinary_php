<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Integration\TestHelpers;

use Cloudinary\Api\ApiClient;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

/**
 * Class MockApiClient
 */
class MockApiClient extends ApiClient
{
    /**
     * @var MockHandler
     */
    public $mockHandler;

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
}
