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

use Cloudinary\Api\Search\SearchApi;

/**
 * Class MockSearchApi
 */
class MockSearchApi extends SearchApi
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

        $this->apiClient = new MockApiClient($configuration);
    }
}
