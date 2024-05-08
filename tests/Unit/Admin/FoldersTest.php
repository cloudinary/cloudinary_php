<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Admin;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Test\Helpers\MockAdminApi;
use Cloudinary\Test\Helpers\RequestAssertionsTrait;
use Cloudinary\Test\Unit\UnitTestCase;

/**
 * Class FoldersTest
 */
final class FoldersTest extends UnitTestCase
{
    use RequestAssertionsTrait;

    /**
     * Test rename folder.
     *
     * @throws ApiError
     */
    public function testRenameFolder()
    {
        $mockAdminApi = new MockAdminApi();
        $mockAdminApi->renameFolder(self::API_TEST_ASSET_ID, self::API_TEST_ASSET_ID2);

        $lastRequest = $mockAdminApi->getMockHandler()->getLastRequest();

        self::assertRequestPut($lastRequest);
        self::assertRequestUrl($lastRequest, '/folders/' . self::API_TEST_ASSET_ID);
        self::assertRequestBodySubset(
            $lastRequest,
            [
                "to_folder" => self::API_TEST_ASSET_ID2,
            ]
        );
    }
}
