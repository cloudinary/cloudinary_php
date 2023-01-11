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

use Cloudinary\Api\Search\SearchFoldersApi;

/**
 * Class MockSearchFoldersApi
 */
class MockSearchFoldersApi extends SearchFoldersApi
{
    use MockApiTrait;

    /**
     * MockSearchFoldersApi constructor.
     *
     * @param mixed $configuration
     */
    public function __construct($configuration = null)
    {
        parent::__construct($configuration);

        $this->apiClient = new MockApiClient($configuration);
    }
}
