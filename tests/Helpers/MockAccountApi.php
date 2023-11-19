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

use Cloudinary\Api\Provisioning\AccountApi;

/**
 * Class MockAccountApi
 */
class MockAccountApi extends AccountApi
{
    use MockApiTrait;

    /**
     * MockSearchApi constructor.
     *
     * @param mixed $configuration
     */
    public function __construct($configuration = null)
    {
        parent::__construct($configuration);

        $this->accountApiClient = new MockAccountApiClient($configuration);
    }

    /**
     * Returns a mock api client.
     *
     * @return MockAccountApiClient
     */
    public function getApiClient()
    {
        return $this->accountApiClient;
    }
}
