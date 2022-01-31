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

use GuzzleHttp\Handler\MockHandler;

/**
 * Trait MockApiTrait
 */
trait MockApiTrait
{
    /**
     * Returns mock handler.
     *
     * @return MockHandler
     */
    public function getMockHandler()
    {
        return $this->getApiClient()->mockHandler;
    }

    /**
     * Returns a mock api client.
     *
     * @return MockApiClient
     */
    public function getApiClient()
    {
        return $this->apiClient;
    }
}
