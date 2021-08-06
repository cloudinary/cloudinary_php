<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Integration\Admin\Assets;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Exception\NotFound;
use Cloudinary\Asset\AssetType;
use Cloudinary\Asset\DeliveryType;
use Cloudinary\Test\Integration\IntegrationTestCase;
use Cloudinary\Test\Unit\Asset\AssetTestCase;

/**
 * Class DeleteAssetsTest
 */
final class DeleteAssetsTest extends IntegrationTestCase
{
    const CLASS_PREFIX = 'delete_assets_test';

    const TRANSFORMATION           = ['width' => 400, 'height' => 400, 'crop' => 'crop'];
    const TRANSFORMATION_AS_STRING = 'c_crop,h_400,w_400';

    const MULTI_DELETE_OPTION_1 = 'multi_delete_option_1';
    const MULTI_DELETE_OPTION_2 = 'multi_delete_option_2';
    const MULTI_DELETE_1        = 'multi_delete_1';
    const MULTI_DELETE_2        = 'multi_delete_2';
    const DELETE_DERIVED        = 'delete_derived';
    const DELETE_SINGLE         = 'delete_single';
    const PRIVATE_ASSET         = 'private_asset';

    private static $DELETE_PREFIX;
    private static $FULL_DELETE_PREFIX;
    private static $UNIQUE_TEST_TAG_DELETE;
    private static $UNIQUE_TEST_TAG_DELETE_OPTIONS;

    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$DELETE_PREFIX                  = 'delete_by_prefix_' . self::$UNIQUE_TEST_ID;
        self::$FULL_DELETE_PREFIX             = self::CLASS_PREFIX . '_' . self::$DELETE_PREFIX;
        self::$UNIQUE_TEST_TAG_DELETE         = self::CLASS_PREFIX . 'delete_' . self::$UNIQUE_TEST_TAG;
        self::$UNIQUE_TEST_TAG_DELETE_OPTIONS = self::CLASS_PREFIX . 'delete_by_options_' . self::$UNIQUE_TEST_TAG;

        self::createTestAssets(
            [
                self::MULTI_DELETE_OPTION_1,
                self::MULTI_DELETE_OPTION_2 => [
                    'options' => [
                        DeliveryType::KEY => DeliveryType::PRIVATE_DELIVERY,
                    ],
                ],
                self::DELETE_DERIVED,
                self::MULTI_DELETE_1,
                self::MULTI_DELETE_2,
                self::DELETE_SINGLE,
                self::$DELETE_PREFIX,
                [
                    'options' => [
                        'tags' => [self::$UNIQUE_TEST_TAG_DELETE],
                    ],
                ],
                [
                    'options' => [
                        AssetType::KEY => AssetType::RAW,
                        'file'         => self::TEST_DOCX_PATH,
                        'tags'         => [self::$UNIQUE_TEST_TAG_DELETE],
                    ],
                ],
                [
                    'options' => [
                        AssetType::KEY => AssetType::VIDEO,
                        'file'         => self::TEST_VIDEO_PATH,
                        'tags'         => [self::$UNIQUE_TEST_TAG_DELETE],
                    ],
                ],
            ],
            self::CLASS_PREFIX
        );
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestAssets([AssetType::IMAGE, AssetType::RAW, AssetType::VIDEO]);

        parent::tearDownAfterClass();
    }

    /**
     * Calling deleteAssetsByTag() without a resource_type defaults to deleting assets of type image.
     *
     * @throws ApiError
     */
    public function testDeleteAssetsByTagDefaultsToImage()
    {
        $result = self::$adminApi->assetsByTag(self::$UNIQUE_TEST_TAG_DELETE, [AssetType::KEY => AssetType::IMAGE]);

        self::assertEquals(AssetType::IMAGE, $result['resources'][0][AssetType::KEY]);

        $resultDeleting = self::$adminApi->deleteAssetsByTag(self::$UNIQUE_TEST_TAG_DELETE);

        self::assertAssetDeleted($resultDeleting, $result['resources'][0]['public_id']);

        $result = self::$adminApi->assetsByTag(self::$UNIQUE_TEST_TAG_DELETE, [AssetType::KEY => AssetType::IMAGE]);

        self::assertEmpty($result['resources']);

        $result = self::$adminApi->assetsByTag(self::$UNIQUE_TEST_TAG_DELETE, [AssetType::KEY => AssetType::RAW]);

        self::assertCount(1, $result['resources']);

        $result = self::$adminApi->assetsByTag(self::$UNIQUE_TEST_TAG_DELETE, [AssetType::KEY => AssetType::VIDEO]);

        self::assertCount(1, $result['resources']);
    }

    /**
     * Delete uploaded images by a single public ID given as a string.
     *
     * @throws ApiError
     */
    public function testDeleteSingleAssetByPublicId()
    {
        $result = self::$adminApi->deleteAssets(self::getTestAssetPublicId(self::DELETE_SINGLE));

        self::assertAssetDeleted($result, self::getTestAssetPublicId(self::DELETE_SINGLE));

        $this->expectException(NotFound::class);
        self::$adminApi->asset(self::getTestAssetPublicId(self::DELETE_SINGLE));
    }

    /**
     * Delete multiple uploaded images by public IDs given in an array.
     *
     * @throws ApiError
     */
    public function testDeleteMultipleAssetsByPublicIds()
    {
        $result = self::$adminApi->deleteAssets(
            [
                self::getTestAssetPublicId(self::MULTI_DELETE_1),
                self::getTestAssetPublicId(self::MULTI_DELETE_2),
            ]
        );

        self::assertAssetDeleted($result, self::getTestAssetPublicId(self::MULTI_DELETE_1), 2);
        self::assertAssetDeleted($result, self::getTestAssetPublicId(self::MULTI_DELETE_2), 2);

        $this->expectException(NotFound::class);
        self::$adminApi->asset(self::getTestAssetPublicId(self::MULTI_DELETE_1));

        $this->expectException(NotFound::class);
        self::$adminApi->asset(self::getTestAssetPublicId(self::MULTI_DELETE_2));
    }

    /**
     * Delete uploaded images by public IDs with options.
     *
     * @throws ApiError
     */
    public function testDeleteAssetsByPublicIdWithOptions()
    {
        $result = self::$adminApi->deleteAssets(
            [
                self::getTestAssetPublicId(self::MULTI_DELETE_OPTION_1),
                self::getTestAssetPublicId(self::MULTI_DELETE_OPTION_2),
                'nonexistent_id',
            ],
            [
                DeliveryType::KEY => DeliveryType::PRIVATE_DELIVERY
            ]
        );

        self::assertAssetDeleted(
            $result,
            self::getTestAssetPublicId(self::MULTI_DELETE_OPTION_2),
            1,
            1,
            0,
            2
        );

        $result = self::$adminApi->asset(
            self::getTestAssetPublicId(self::MULTI_DELETE_OPTION_1)
        );

        self::assertValidAsset($result, [AssetType::KEY => AssetType::IMAGE]);

        $result = self::$adminApi->assetsByIds(
            self::getTestAssetPublicId(self::MULTI_DELETE_OPTION_2),
            [
                DeliveryType::KEY => DeliveryType::PRIVATE_DELIVERY,
            ]
        );

        self::assertCount(0, $result['resources']);
    }

    /**
     * Delete uploaded images by prefix.
     *
     * @throws ApiError
     */
    public function testDeleteAssetsByPrefix()
    {
        $result = self::$adminApi->deleteAssetsByPrefix(self::$FULL_DELETE_PREFIX);

        self::assertAssetDeleted($result, self::getTestAssetPublicId(self::$DELETE_PREFIX));

        $this->expectException(NotFound::class);
        self::$adminApi->asset(self::getTestAssetPublicId(self::$DELETE_PREFIX));
    }

    /**
     * Delete derived images only.
     *
     * @throws ApiError
     */
    public function testDeleteDerivedImagesOnly()
    {
        $result = self::$uploadApi->explicit(
            self::getTestAssetPublicId(self::DELETE_DERIVED),
            [
                DeliveryType::KEY => DeliveryType::UPLOAD,
                'eager'           => [self::TRANSFORMATION],
            ]
        );

        self::assertCount(1, $result['eager']);
        self::assertValidTransformationRepresentation(
            $result['eager'][0],
            [
                'transformation' => self::TRANSFORMATION_AS_STRING,
                'format'         => AssetTestCase::IMG_EXT_GIF,
                'bytes'          => 43,
                'width'          => 1,
                'height'         => 1,
            ]
        );

        $asset = self::$adminApi->asset(
            self::getTestAssetPublicId(self::DELETE_DERIVED)
        );

        self::assertCount(1, $asset['derived']);
        self::assertValidDerivedAsset(
            $asset['derived'][0],
            [
                'transformation' => self::TRANSFORMATION_AS_STRING,
                'format'         => AssetTestCase::IMG_EXT_GIF,
                'bytes'          => 43,
            ]
        );

        $result = self::$adminApi->deleteAssets(
            [
                self::getTestAssetPublicId(self::DELETE_DERIVED)
            ],
            [
                'keep_original' => true,
            ]
        );

        self::assertAssetDeleted(
            $result,
            self::getTestAssetPublicId(self::DELETE_DERIVED),
            1,
            0,
            1
        );

        $asset = self::$adminApi->asset(
            self::getTestAssetPublicId(self::DELETE_DERIVED)
        );

        self::assertValidAsset($asset);
        self::assertEmpty($asset['derived']);
    }

    /**
     * Delete assets by options.
     *
     * @throws ApiError
     */
    public function testDeleteAssetsByOptions()
    {
        if (! self::shouldRunDestructiveTests()) {
            self::markTestSkipped('Skipping DeleteAssetsByOptions test');
        }

        self::createTestAssets(
            [
                self::PRIVATE_ASSET => [
                    'options' => [
                        DeliveryType::KEY => DeliveryType::PRIVATE_DELIVERY,
                        AssetType::KEY    => AssetType::RAW,
                        'file'            => self::TEST_DOCX_PATH,
                        'tags'            => [self::$UNIQUE_TEST_TAG_DELETE_OPTIONS],
                    ],
                ],
                [
                    'options' => [
                        DeliveryType::KEY => DeliveryType::UPLOAD,
                        AssetType::KEY    => AssetType::RAW,
                        'file'            => self::TEST_DOCX_PATH,
                        'tags'            => [self::$UNIQUE_TEST_TAG_DELETE_OPTIONS],
                    ],
                ]
            ]
        );
        $result = self::$adminApi->deleteAllAssets(
            [
                DeliveryType::KEY => DeliveryType::PRIVATE_DELIVERY,
                AssetType::KEY    => AssetType::RAW,
            ]
        );

        self::assertAssetDeleted($result, self::getTestAssetPublicId(self::PRIVATE_ASSET));

        $assets = self::$adminApi->assetsByTag(
            self::$UNIQUE_TEST_TAG_DELETE_OPTIONS,
            [
                AssetType::KEY => AssetType::RAW
            ]
        );

        self::assertCount(1, $assets['resources']);
        self::assertValidAsset(
            $assets['resources'][0],
            [
                DeliveryType::KEY => DeliveryType::UPLOAD,
                AssetType::KEY    => AssetType::RAW
            ]
        );
    }
}
