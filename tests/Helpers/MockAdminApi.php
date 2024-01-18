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

use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Api\ApiClient;
use Cloudinary\Configuration\Configuration;

/**
 * Class MockAdminApi
 */
class MockAdminApi extends AdminApi
{
    use MockApiTrait;

    /**
     * MockAdminApi constructor.
     *
     * @param mixed $configuration
     */
    public function __construct($configuration = null)
    {
        parent::__construct($configuration);

        $this->apiClient = new MockApiClient($configuration);

        $apiV2Configuration = new Configuration($configuration);
        $apiV2Configuration->api->apiVersion = '2';

        $this->apiV2Client = new MockApiClient($apiV2Configuration);
    }
}
