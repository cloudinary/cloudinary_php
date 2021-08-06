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
use Cloudinary\Asset\AssetType;
use Cloudinary\Asset\DeliveryType;
use Cloudinary\Asset\ModerationStatus;
use Cloudinary\Asset\ModerationType;
use Cloudinary\Test\Helpers\Addon;
use Cloudinary\Test\Integration\IntegrationTestCase;
use Cloudinary\Test\Unit\Asset\AssetTestCase;
use Exception;

/**
 * Class ListAssetsTest
 */
final class ListAssetsTest extends IntegrationTestCase
{
    const TEST_ASSET   = 'test_asset';
    const CLASS_PREFIX = 'list_assets_test';

    private static $UNIQUE_CONTEXT;
    private static $UNIQUE_CONTEXT_KEY;
    private static $UNIQUE_CONTEXT_VALUE;
    private static $UNIQUE_TEST_TAG_TO_ONE_IMAGE_ASSET;
    private static $UNIQUE_PREFIX;
    private static $FULL_UNIQUE_PREFIX;

    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$UNIQUE_PREFIX                      = 'prefix_' . self::$UNIQUE_TEST_ID;
        self::$FULL_UNIQUE_PREFIX                 = self::CLASS_PREFIX . '_' . self::$UNIQUE_PREFIX;
        self::$UNIQUE_CONTEXT_KEY                 = self::CLASS_PREFIX . 'context_key_' . self::$UNIQUE_TEST_ID;
        self::$UNIQUE_CONTEXT_VALUE               = self::CLASS_PREFIX . 'context_value_' . self::$UNIQUE_TEST_ID;
        self::$UNIQUE_CONTEXT                     = self::$UNIQUE_CONTEXT_KEY . '=' . self::$UNIQUE_CONTEXT_VALUE;
        self::$UNIQUE_TEST_TAG_TO_ONE_IMAGE_ASSET = self::CLASS_PREFIX . 'unique_tag_to_one_image_asset_' .
                                                    self::$UNIQUE_TEST_TAG;

        self::createTestAssets(
            [
                self::$UNIQUE_PREFIX => [
                    'options' => [
                        'tags'              => [self::$UNIQUE_TEST_TAG_TO_ONE_IMAGE_ASSET],
                        'context'           => self::$UNIQUE_CONTEXT,
                        ModerationType::KEY => ModerationType::MANUAL,
                    ]
                ],
                self::TEST_ASSET,
                [
                    'options' => [
                        'context'      => self::$UNIQUE_CONTEXT,
                        'file'         => self::TEST_DOCX_PATH,
                        AssetType::KEY => AssetType::RAW,
                    ],
                ],
                [
                    'options' => [
                        'context'      => self::$UNIQUE_CONTEXT,
                        'file'         => self::TEST_VIDEO_PATH,
                        AssetType::KEY => AssetType::VIDEO,
                    ],
                ],
            ],
            self::CLASS_PREFIX
        );

        self::fetchRemoteTestAsset(
            AssetTestCase::TEST_SOCIAL_PROFILE_ID,
            [DeliveryType::KEY => DeliveryType::FACEBOOK]
        );
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestAssets([AssetType::IMAGE, AssetType::RAW, AssetType::VIDEO]);

        parent::tearDownAfterClass();
    }

    /**
     * Get a list of available asset types.
     */
    public function testListAssetTypes()
    {
        $result = self::$adminApi->assetTypes();

        self::assertNotEmpty($result['resource_types']);
        self::assertContains(AssetType::IMAGE, $result['resource_types']);
        self::assertContains(AssetType::RAW, $result['resource_types']);
        self::assertContains(AssetType::VIDEO, $result['resource_types']);
    }

    /**
     * Get a list of all assets (defaults to asset type images).
     */
    public function testListAllImages()
    {
        // The assets method defaults to fetching images if resource_type is omitted
        $result = self::$adminApi->assets([DeliveryType::KEY => DeliveryType::UPLOAD]);

        self::assertValidAsset($result['resources'][0], [AssetType::KEY => AssetType::IMAGE]);
    }

    /**
     * Get a list of all images with delivery type upload.
     */
    public function testListUploadedImages()
    {
        $result = self::$adminApi->assets([DeliveryType::KEY => DeliveryType::UPLOAD]);

        self::assertValidAsset(
            $result['resources'][0],
            [
                DeliveryType::KEY => DeliveryType::UPLOAD,
                AssetType::KEY    => AssetType::IMAGE,
            ]
        );
    }

    /**
     * Get a list of all images with delivery type upload and a given prefix.
     */
    public function testListUploadedImagesWithPrefix()
    {
        $result = self::$adminApi->assets(
            [
                DeliveryType::KEY => DeliveryType::UPLOAD,
                'prefix'          => self::$FULL_UNIQUE_PREFIX,
            ]
        );

        self::assertCount(1, $result['resources']);
        self::assertValidAsset(
            $result['resources'][0],
            [
                'public_id'       => self::getTestAssetPublicId(self::$UNIQUE_PREFIX),
                DeliveryType::KEY => DeliveryType::UPLOAD,
                AssetType::KEY    => AssetType::IMAGE,
            ]
        );
    }

    /**
     * Get a list of all images with delivery type facebook.
     */
    public function testListFacebookImages()
    {
        $result = self::$adminApi->assets([DeliveryType::KEY => DeliveryType::FACEBOOK]);

        self::assertValidAsset(
            $result['resources'][0],
            [
                DeliveryType::KEY => DeliveryType::FACEBOOK,
                AssetType::KEY    => AssetType::IMAGE,
            ]
        );
    }

    /**
     * Facebook images assets do not contain height or width.
     */
    public function testFacebookImagesWidthHeight()
    {
        $result = self::$adminApi->assets([DeliveryType::KEY => DeliveryType::FACEBOOK]);

        self::assertArrayNotHasKey('height', $result['resources'][0]);
        self::assertArrayNotHasKey('width', $result['resources'][0]);
    }

    /**
     * List raw uploaded files.
     */
    public function testListRawUploadedFiles()
    {
        $result = self::$adminApi->assets([AssetType::KEY => AssetType::RAW]);

        self::assertValidAsset($result['resources'][0], [AssetType::KEY => AssetType::RAW]);
    }

    /**
     * Get a single uploaded asset with a given ID passed as a string.
     */
    public function testListUploadedAssetsById()
    {
        $result = self::$adminApi->assetsByIds(self::getTestAssetPublicId(self::$UNIQUE_PREFIX));

        self::assertValidAsset(
            $result['resources'][0],
            [
                'public_id'    => self::getTestAssetPublicId(self::$UNIQUE_PREFIX),
                AssetType::KEY => AssetType::IMAGE,
            ]
        );
        self::assertCount(1, $result['resources']);
    }

    /**
     * Get uploaded assets matching the IDs passed as an array.
     */
    public function testListUploadedAssetsByIds()
    {
        $result = self::$adminApi->assetsByIds(
            [
                self::getTestAssetPublicId(self::$UNIQUE_PREFIX),
                self::getTestAssetPublicId(self::TEST_ASSET)
            ]
        );

        self::assertValidAsset($result['resources'][0]);
        self::assertValidAsset($result['resources'][1]);
        self::assertCount(2, $result['resources']);
    }

    /**
     * Get images by tag.
     */
    public function testListImagesByTag()
    {
        $result = self::$adminApi->assetsByTag(self::$UNIQUE_TEST_TAG_TO_ONE_IMAGE_ASSET, ['tags' => true]);

        self::assertValidAsset($result['resources'][0], [AssetType::KEY => AssetType::IMAGE]);
        self::assertContains(self::$UNIQUE_TEST_TAG_TO_ONE_IMAGE_ASSET, $result['resources'][0]['tags']);
        self::assertCount(1, $result['resources']);
    }

    /**
     * Get raw files by tag.
     */
    public function testListRawFilesByTag()
    {
        $result = self::$adminApi->assetsByTag(
            self::$UNIQUE_TEST_TAG,
            [AssetType::KEY => AssetType::RAW, 'tags' => true]
        );

        self::assertValidAsset($result['resources'][0], [AssetType::KEY => AssetType::RAW]);
        self::assertContains(self::$UNIQUE_TEST_TAG, $result['resources'][0]['tags']);
        self::assertCount(1, $result['resources']);
    }

    /**
     * Get images by context key.
     */
    public function testListImagesByContextKey()
    {
        $result = self::$adminApi->assetsByContext(self::$UNIQUE_CONTEXT_KEY);

        self::assertValidAsset($result['resources'][0]);
        self::assertCount(1, $result['resources']);
        self::assertCount(1, $result['resources'][0]['context']['custom']);
        self::assertEquals(
            self::$UNIQUE_CONTEXT_VALUE,
            $result['resources'][0]['context']['custom'][self::$UNIQUE_CONTEXT_KEY]
        );
    }

    /**
     * Get video files by context key and value.
     */
    public function testListVideosByContextKeyValue()
    {
        $result = self::$adminApi->assetsByContext(
            self::$UNIQUE_CONTEXT_KEY,
            self::$UNIQUE_CONTEXT_VALUE,
            [AssetType::KEY => AssetType::VIDEO]
        );

        self::assertValidAsset($result['resources'][0], [AssetType::KEY => AssetType::VIDEO]);
        self::assertCount(1, $result['resources']);
        self::assertCount(1, $result['resources'][0]['context']['custom']);
        self::assertEquals(
            self::$UNIQUE_CONTEXT_VALUE,
            $result['resources'][0]['context']['custom'][self::$UNIQUE_CONTEXT_KEY]
        );
    }

    /**
     * Get images pending manual moderation.
     */
    public function testListImagesPendingManualModeration()
    {
        $result = self::$adminApi->assetsByModeration(ModerationType::MANUAL, ModerationStatus::PENDING);

        self::assertValidAsset($result['resources'][0]);
        self::assertGreaterThanOrEqual(1, count($result['resources']));
    }

    /**
     * Get images automatically approved by the WebPurify add-on.
     *
     * @throws ApiError
     * @throws Exception
     */
    public function testListImagesApprovedByWebPurify()
    {
        if (! self::shouldTestAddOn(Addon::WEBPURIFY)) {
            self::markTestSkipped('Skipping WebPurify test');
        }

        self::uploadTestAssetImage([ModerationType::KEY => ModerationType::WEBPURIFY]);

        self::retryAssertionIfThrows(
            function () {
                $result = self::$adminApi->assetsByModeration(
                    ModerationType::WEBPURIFY,
                    ModerationStatus::APPROVED
                );
                self::assertGreaterThanOrEqual(1, count($result['resources']));
                self::assertEquals(AssetType::IMAGE, $result['resources'][0][AssetType::KEY]);
            },
            3,
            1,
            'Unable to list images approved by WebPurify'
        );
    }
}
