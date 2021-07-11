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

use Cloudinary\Test\Helpers\MockAdminApi;
use Cloudinary\Test\Helpers\RequestAssertionsTrait;
use Cloudinary\Test\Unit\UnitTestCase;

/**
 * Class AssetsTest
 */
final class AssetsTest extends UnitTestCase
{
    use RequestAssertionsTrait;

    public function restoreDeletedAssetSpecificVersionDataProvider()
    {
        return [
            [
                'publicIds'  => [self::$UNIQUE_TEST_ID],
                'options'    => [
                    'versions' => ['293272f6bd9ec6ae9fa643e295b4dd1b'],
                ],
                'url'        => '/resources/image/upload/restore',
                'bodyFields' => [
                    'public_ids' => [self::$UNIQUE_TEST_ID],
                    'versions'   => ['293272f6bd9ec6ae9fa643e295b4dd1b'],
                ],
            ],
            [
                'publicIds'  => [self::$UNIQUE_TEST_ID, self::$UNIQUE_TEST_ID2],
                'options'    => [
                    'versions'      => ['9fa643e295b4dd1b293272f6bd9ec6ae', 'b4dd1b293272f6bd9fa643e2959ec6ae'],
                    'resource_type' => 'raw',
                    'type'          => 'private',
                ],
                'url'        => '/resources/raw/private/restore',
                'bodyFields' => [
                    'public_ids' => [self::$UNIQUE_TEST_ID, self::$UNIQUE_TEST_ID2],
                    'versions'   => ['9fa643e295b4dd1b293272f6bd9ec6ae', 'b4dd1b293272f6bd9fa643e2959ec6ae'],
                ],
            ],
        ];
    }

    /**
     * Restore specific versions of a deleted asset.
     *
     * @dataProvider restoreDeletedAssetSpecificVersionDataProvider
     *
     * @param array  $publicIds
     * @param array  $options
     * @param string $url
     * @param array  $bodyFields
     */
    public function testRestoreDeletedAssetSpecificVersion($publicIds, $options, $url, $bodyFields)
    {
        $mockAdminApi = new MockAdminApi();
        $mockAdminApi->restore($publicIds, $options);
        $lastRequest = $mockAdminApi->getMockHandler()->getLastRequest();

        self::assertRequestUrl($lastRequest, $url);
        self::assertRequestJsonBodySubset($lastRequest, $bodyFields);
    }

    /**
     * Test update assets complex fields serialization.
     */
    public function testUpdateAssetFields()
    {
        $mockAdminApi = new MockAdminApi();
        $mockAdminApi->update(self::$UNIQUE_TEST_ID, ['metadata' => ['key'=>'value']]);
        $lastRequest = $mockAdminApi->getMockHandler()->getLastRequest();

        self::assertRequestUrl($lastRequest, '/resources/image/upload/' . self::$UNIQUE_TEST_ID);
        self::assertRequestBodySubset($lastRequest, ['metadata'=>'key=value']);
    }
}
