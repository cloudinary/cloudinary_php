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
        $mockAdminApi->update(
            self::$UNIQUE_TEST_ID,
            [
                'metadata'            => ['key' => 'value'],
                'asset_folder'        => 'asset_folder',
                'unique_display_name' => true,
            ]
        );
        $lastRequest = $mockAdminApi->getMockHandler()->getLastRequest();

        self::assertRequestUrl($lastRequest, '/resources/image/upload/' . self::$UNIQUE_TEST_ID);
        self::assertRequestBodySubset(
            $lastRequest,
            [
                'metadata'            => 'key=value',
                'asset_folder'        => 'asset_folder',
                'unique_display_name' => true,
            ]
        );
    }

    /**
     * Test related assets.
     */
    public function testRelatedAssets()
    {
        $testIds = [
            'image/upload/' . self::$UNIQUE_TEST_ID,
            'raw/upload/' . self::$UNIQUE_TEST_ID,
        ];

        $mockAdminApi = new MockAdminApi();
        $mockAdminApi->addRelatedAssets(
            self::$UNIQUE_TEST_ID,
            $testIds
        );

        $lastRequest = $mockAdminApi->getMockHandler()->getLastRequest();

        self::assertRequestUrl($lastRequest, '/resources/related_assets/image/upload/' . self::$UNIQUE_TEST_ID);
        self::assertRequestJsonBodySubset(
            $lastRequest,
            [
                'assets_to_relate' => $testIds
            ]
        );

        $mockAdminApi = new MockAdminApi();
        $mockAdminApi->deleteRelatedAssets(
            self::$UNIQUE_TEST_ID,
            $testIds
        );

        $lastRequest = $mockAdminApi->getMockHandler()->getLastRequest();

        self::assertRequestUrl($lastRequest, '/resources/related_assets/image/upload/' . self::$UNIQUE_TEST_ID);
        self::assertRequestJsonBodySubset(
            $lastRequest,
            [
                'assets_to_unrelate' => $testIds
            ]
        );
    }

    /**
     * Test related assets by asset Ids.
     */
    public function testRelatedAssetsByAssetIds()
    {
        $testAssetIds = [
            self::API_TEST_ASSET_ID2,
            self::API_TEST_ASSET_ID3,
        ];

        $mockAdminApi = new MockAdminApi();
        $mockAdminApi->addRelatedAssetsByAssetIds(
            self::API_TEST_ASSET_ID,
            $testAssetIds
        );

        $lastRequest = $mockAdminApi->getMockHandler()->getLastRequest();

        self::assertRequestUrl($lastRequest, '/resources/related_assets/' . self::API_TEST_ASSET_ID);
        self::assertRequestJsonBodySubset(
            $lastRequest,
            [
                'assets_to_relate' => $testAssetIds
            ]
        );

        $mockAdminApi = new MockAdminApi();
        $mockAdminApi->deleteRelatedAssetsByAssetIds(
            self::API_TEST_ASSET_ID,
            $testAssetIds
        );

        $lastRequest = $mockAdminApi->getMockHandler()->getLastRequest();

        self::assertRequestUrl($lastRequest, '/resources/related_assets/' . self::API_TEST_ASSET_ID);
        self::assertRequestJsonBodySubset(
            $lastRequest,
            [
                'assets_to_unrelate' => $testAssetIds
            ]
        );
    }
}
