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
use Cloudinary\Test\Helpers\MockAdminApi;
use Cloudinary\Test\Helpers\RequestAssertionsTrait;
use Cloudinary\Test\Integration\IntegrationTestCase;
use Cloudinary\Test\Unit\Asset\AssetTestCase;
use Cloudinary\Test\Unit\UnitTestCase;

/**
 * Class AssetsTest
 */
final class AssetsTest extends UnitTestCase
{
    use RequestAssertionsTrait;


    /**
     * @return array[]
     */
    public function listAssetsFieldsDataProvider()
    {
        return [
            [
                'options'     => [
                    'fields' => ['tags', 'secure_url'],
                ],
                'url'         => '/resources/image',
                'queryParams' => [
                    'fields' => 'tags,secure_url',
                ],
            ],
            [
                'options'     => [
                    'fields' => 'context,url',
                ],
                'url'         => '/resources/image',
                'queryParams' => [
                    'fields' => 'context,url',
                ],
            ],
            [
                'options'     => [
                    'fields' => "",
                ],
                'url'         => '/resources/image',
                'queryParams' => [],
            ],
        ];
    }

    /**
     * Test list assets fields serialization.
     *
     * @dataProvider listAssetsFieldsDataProvider
     */
    public function testListAssetFields($options, $url, $queryParams)
    {
        $mockAdminApi = new MockAdminApi();
        $mockAdminApi->assets($options);
        $lastRequest = $mockAdminApi->getMockHandler()->getLastRequest();

        self::assertRequestUrl($lastRequest, $url);
        self::assertRequestQueryStringSubset($lastRequest, $queryParams);
    }

    /**
     * @return array[]
     */
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
     * @return array[]
     */
    public function restoreDeletedAssetByAssetIDSpecificVersionDataProvider()
    {
        return [
            [
                'assetIds'  => self::API_TEST_ASSET_ID,
                'options'    => [
                    'versions' => ['293272f6bd9ec6ae9fa643e295b4dd1b'],
                ],
                'url'        => '/resources/restore',
                'bodyFields' => [
                    'asset_ids' => [self::API_TEST_ASSET_ID],
                    'versions'   => ['293272f6bd9ec6ae9fa643e295b4dd1b'],
                ],
            ],
            [
                'assetIds'  => [self::API_TEST_ASSET_ID2, self::API_TEST_ASSET_ID3],
                'options'    => [
                    'versions'      => ['9fa643e295b4dd1b293272f6bd9ec6ae', 'b4dd1b293272f6bd9fa643e2959ec6ae'],
                ],
                'url'        => '/resources/restore',
                'bodyFields' => [
                    'asset_ids' => [self::API_TEST_ASSET_ID2, self::API_TEST_ASSET_ID3],
                    'versions'   => ['9fa643e295b4dd1b293272f6bd9ec6ae', 'b4dd1b293272f6bd9fa643e2959ec6ae'],
                ],
            ],
        ];
    }

    /**
     * Restore specific versions of a deleted asset by asset IDs.
     *
     * @dataProvider restoreDeletedAssetByAssetIDSpecificVersionDataProvider
     *
     * @param array|string $assetIds
     * @param array        $options
     * @param string       $url
     * @param array        $bodyFields
     */
    public function testRestoreDeletedAssetByAssetIDSpecificVersion($assetIds, $options, $url, $bodyFields)
    {
        $mockAdminApi = new MockAdminApi();
        $mockAdminApi->restoreByAssetIds($assetIds, $options);
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
                'visual_search'       => true,
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
                'visual_search'       => true,
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
                'assets_to_relate' => $testIds,
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
                'assets_to_unrelate' => $testIds,
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
                'assets_to_relate' => $testAssetIds,
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
                'assets_to_unrelate' => $testAssetIds,
            ]
        );
    }


    /**
     * @return array[]
     */
    public function visualSearchDataProvider()
    {
        return [
            [
                'options'    => [
                    'image_url' => AssetTestCase::FETCH_IMAGE_URL,
                ],
                'url'        => '/resources/visual_search',
                'bodyFields' => [
                    'image_url' => AssetTestCase::FETCH_IMAGE_URL,
                ],
            ],
            [
                'options'    => [
                    'image_asset_id' => self::API_TEST_ASSET_ID,
                ],
                'url'        => '/resources/visual_search',
                'bodyFields' => [
                    'image_asset_id' => self::API_TEST_ASSET_ID,
                ],
            ],
            [
                'options'    => [
                    'text' => 'sample image',
                ],
                'url'        => '/resources/visual_search',
                'bodyFields' => [
                    'text' => 'sample image',
                ],
            ],
        ];
    }

    /**
     * Test Visual search.
     *
     * @dataProvider visualSearchDataProvider
     *
     * @param array  $options
     * @param string $url
     * @param array  $bodyFields
     *
     * @throws ApiError
     */
    public function testVisualSearch($options, $url, $bodyFields)
    {
        $mockAdminApi = new MockAdminApi();
        $mockAdminApi->visualSearch($options);
        $lastRequest = $mockAdminApi->getMockHandler()->getLastRequest();

        self::assertRequestUrl($lastRequest, $url);
        self::assertRequestBodySubset($lastRequest, $bodyFields);
    }

    /**
     * Test Visual search using image file.
     *
     * @throws ApiError
     */
    public function testVisualSearchImageFile()
    {
        $mockAdminApi = new MockAdminApi();
        $mockAdminApi->visualSearch(['image_file' => IntegrationTestCase::TEST_IMAGE_PATH]);
        $lastRequest = $mockAdminApi->getMockHandler()->getLastRequest();

        self::assertRequestUrl($lastRequest, '/resources/visual_search');
        $body = $lastRequest->getBody()->getContents();

        self::assertStringContainsString('image_file', $body);
        self::assertStringContainsString('Content-Length: 5110', $body);
    }
}
