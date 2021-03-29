<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Integration\Admin;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Exception\NotFound;
use Cloudinary\Api\Metadata\StringMetadataField;
use Cloudinary\Asset\AssetType;
use Cloudinary\Asset\DeliveryType;
use Cloudinary\Asset\ModerationStatus;
use Cloudinary\Asset\ModerationType;
use Cloudinary\Test\Helpers\Addon;
use Cloudinary\Test\Integration\IntegrationTestCase;
use Cloudinary\Test\Unit\Asset\AssetTestCase;
use Exception;

/**
 * Class AssetsTest
 */
final class AssetsTest extends IntegrationTestCase
{
    const TRANSFORMATION           = ['width' => 400, 'height' => 400, 'crop' => 'crop'];
    const TRANSFORMATION_AS_STRING = 'c_crop,h_400,w_400';

    private static $PUBLIC_ID_TEST;
    private static $PUBLIC_ID_TEST_2;
    private static $DELETE_DERIVED_IMAGE_PUBLIC_ID;
    private static $MULTI_DELETE_OPTION_1_PUBLIC_ID;
    private static $MULTI_DELETE_OPTION_2_PUBLIC_ID;
    private static $MULTI_DELETE_1_PUBLIC_ID;
    private static $MULTI_DELETE_2_PUBLIC_ID;
    private static $DELETE_SINGLE_PUBLIC_ID;
    private static $RESTORE_PUBLIC_ID;
    private static $UNIQUE_CONTEXT;
    private static $UNIQUE_CONTEXT_KEY;
    private static $UNIQUE_CONTEXT_VALUE;
    private static $UNIQUE_DELETE_PREFIX;
    private static $UNIQUE_DELETE_PREFIX_PUBLIC_ID;
    private static $UNIQUE_TEST_TAG_TO_ONE_IMAGE_ASSET;
    private static $UNIQUE_TEST_TAG_DELETE;
    private static $UNIQUE_TEST_TAG_DELETE_OPTIONS;
    private static $UNIQUE_TEST_TAG_RESTORE;
    private static $UNIQUE_TEST_METADATA;
    private static $UNIQUE_TEST_METADATA_EXTERNAL_ID;
    private static $UNIQUE_TEST_METADATA_DEFAULT_VALUE;
    private static $UNIQUE_PREFIX;
    private static $ASSETS_PREFIX;
    private static $BACKUP_1_PUBLIC_ID;
    private static $BACKUP_2_PUBLIC_ID;
    private static $BACKUP_3_PUBLIC_ID;
    private static $BACKUP_1_ASSET_FIRST;
    private static $BACKUP_1_ASSET_SECOND;
    private static $BACKUP_2_ASSET;
    private static $BACKUP_3_ASSET;

    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$ASSETS_PREFIX                      = $pref = 'assets_';
        self::$UNIQUE_PREFIX                      = $pref . 'prefix_' . self::$UNIQUE_TEST_ID;
        self::$PUBLIC_ID_TEST                     = self::$UNIQUE_PREFIX . '_public_id';
        self::$PUBLIC_ID_TEST_2                   = $pref . 'public_id_2' . self::$UNIQUE_TEST_ID;
        self::$DELETE_DERIVED_IMAGE_PUBLIC_ID     = $pref . 'delete_derived_public_id_' . self::$UNIQUE_TEST_ID;
        self::$MULTI_DELETE_1_PUBLIC_ID           = $pref . 'multi_delete_1_public_id_' . self::$UNIQUE_TEST_ID;
        self::$MULTI_DELETE_2_PUBLIC_ID           = $pref . 'multi_delete_2_public_id_' . self::$UNIQUE_TEST_ID;
        self::$MULTI_DELETE_OPTION_1_PUBLIC_ID    = $pref . 'multi_delete_options_1_public_id_' . self::$UNIQUE_TEST_ID;
        self::$MULTI_DELETE_OPTION_2_PUBLIC_ID    = $pref . 'multi_delete_options_2_public_id_' . self::$UNIQUE_TEST_ID;
        self::$DELETE_SINGLE_PUBLIC_ID            = $pref . 'delete_single_public_id_' . self::$UNIQUE_TEST_ID;
        self::$RESTORE_PUBLIC_ID                  = $pref . 'restore_public_id_' . self::$UNIQUE_TEST_ID;
        self::$BACKUP_1_PUBLIC_ID                 = $pref . 'backup_public_id_1_' . self::$UNIQUE_TEST_ID;
        self::$BACKUP_2_PUBLIC_ID                 = $pref . 'backup_public_id_2_' . self::$UNIQUE_TEST_ID;
        self::$BACKUP_3_PUBLIC_ID                 = $pref . 'backup_public_id_3_' . self::$UNIQUE_TEST_ID;
        self::$UNIQUE_CONTEXT_KEY                 = $pref . 'context_key_' . self::$UNIQUE_TEST_ID;
        self::$UNIQUE_CONTEXT_VALUE               = $pref . 'context_value_' . self::$UNIQUE_TEST_ID;
        self::$UNIQUE_CONTEXT                     = self::$UNIQUE_CONTEXT_KEY . '=' . self::$UNIQUE_CONTEXT_VALUE;
        self::$UNIQUE_TEST_TAG_DELETE             = $pref . 'delete_' . self::$UNIQUE_TEST_TAG;
        self::$UNIQUE_TEST_TAG_DELETE_OPTIONS     = $pref . 'delete_by_options_' . self::$UNIQUE_TEST_TAG;
        self::$UNIQUE_TEST_TAG_RESTORE            = $pref . 'restore_' . self::$UNIQUE_TEST_TAG;
        self::$UNIQUE_TEST_TAG_TO_ONE_IMAGE_ASSET = $pref . 'unique_tag_to_one_image_asset_' . self::$UNIQUE_TEST_TAG;
        self::$UNIQUE_DELETE_PREFIX               = $pref . 'delete_by_prefix_' . self::$UNIQUE_TEST_ID;
        self::$UNIQUE_DELETE_PREFIX_PUBLIC_ID     = self::$UNIQUE_DELETE_PREFIX . '_public_id';
        self::$UNIQUE_TEST_METADATA_EXTERNAL_ID   = $pref . 'metadata_external_id_' . self::$UNIQUE_TEST_ID;
        self::$UNIQUE_TEST_METADATA_DEFAULT_VALUE = $pref . 'metadata_default_value_' . self::$UNIQUE_TEST_ID;

        self::$UNIQUE_TEST_METADATA = new StringMetadataField(self::$UNIQUE_TEST_METADATA_EXTERNAL_ID);
        self::$UNIQUE_TEST_METADATA->setExternalId(self::$UNIQUE_TEST_METADATA_EXTERNAL_ID);
        self::$UNIQUE_TEST_METADATA->setDefaultValue(self::$UNIQUE_TEST_METADATA_DEFAULT_VALUE);
        self::$UNIQUE_TEST_METADATA->setMandatory(true);
        self::$adminApi->addMetadataField(self::$UNIQUE_TEST_METADATA);

        self::$BACKUP_1_ASSET_FIRST  = self::uploadTestAssetImage(
            [
                'public_id' => self::$BACKUP_1_PUBLIC_ID,
                'backup'    => true,
            ]
        );
        self::$BACKUP_1_ASSET_SECOND = self::uploadTestAssetImage(
            [
                'public_id'      => self::$BACKUP_1_PUBLIC_ID,
                'backup'         => true,
                'transformation' => ['angle' => 0],
            ]
        );
        self::$BACKUP_2_ASSET        = self::uploadTestAssetImage(
            [
                'public_id' => self::$BACKUP_2_PUBLIC_ID,
                'backup'    => true,
            ]
        );
        self::$BACKUP_3_ASSET        = self::uploadTestAssetImage(
            [
                'public_id'      => self::$BACKUP_3_PUBLIC_ID,
                'backup'         => true,
                'transformation' => ['angle' => 0],
            ]
        );
        self::uploadTestAssetImage(
            [
                'tags'              => [self::$UNIQUE_TEST_TAG_TO_ONE_IMAGE_ASSET],
                'context'           => self::$UNIQUE_CONTEXT,
                ModerationType::KEY => ModerationType::MANUAL,
                'public_id'         => self::$PUBLIC_ID_TEST,
            ]
        );
        self::uploadTestAssetImage(
            [
                'public_id' => self::$RESTORE_PUBLIC_ID,
                'tags'      => [self::$UNIQUE_TEST_TAG_RESTORE],
                'backup'    => true,
            ]
        );
        self::uploadTestAssetImage(['public_id' => self::$MULTI_DELETE_OPTION_1_PUBLIC_ID]);
        self::uploadTestAssetImage(
            [
                DeliveryType::KEY => DeliveryType::PRIVATE_DELIVERY,
                'public_id'       => self::$MULTI_DELETE_OPTION_2_PUBLIC_ID,
            ]
        );
        self::uploadTestAssetImage(['public_id' => self::$DELETE_DERIVED_IMAGE_PUBLIC_ID]);
        self::uploadTestAssetImage(['public_id' => self::$UNIQUE_DELETE_PREFIX_PUBLIC_ID]);
        self::uploadTestAssetImage(['public_id' => self::$PUBLIC_ID_TEST_2]);
        self::uploadTestAssetImage(['public_id' => self::$MULTI_DELETE_1_PUBLIC_ID]);
        self::uploadTestAssetImage(['public_id' => self::$MULTI_DELETE_2_PUBLIC_ID]);
        self::uploadTestAssetImage(['public_id' => self::$DELETE_SINGLE_PUBLIC_ID]);
        self::uploadTestAssetFile(['context' => self::$UNIQUE_CONTEXT]);
        self::uploadTestAssetVideo(['context' => self::$UNIQUE_CONTEXT]);
        self::uploadTestAssetImage(['tags' => [self::$UNIQUE_TEST_TAG_DELETE]]);
        self::uploadTestAssetFile(['tags' => [self::$UNIQUE_TEST_TAG_DELETE]]);
        self::uploadTestAssetVideo(['tags' => [self::$UNIQUE_TEST_TAG_DELETE]]);
        self::fetchRemoteTestAsset(
            AssetTestCase::TEST_SOCIAL_PROFILE_ID,
            [DeliveryType::KEY => DeliveryType::FACEBOOK]
        );
    }

    /**
     * @throws ApiError
     */
    public static function tearDownAfterClass()
    {
        self::$adminApi->deleteAssetsByTag(self::$UNIQUE_TEST_TAG, [AssetType::KEY => AssetType::IMAGE]);
        self::$adminApi->deleteAssetsByTag(self::$UNIQUE_TEST_TAG, [AssetType::KEY => AssetType::RAW]);
        self::$adminApi->deleteAssetsByTag(self::$UNIQUE_TEST_TAG, [AssetType::KEY => AssetType::VIDEO]);
        self::$adminApi->deleteMetadataField(self::$UNIQUE_TEST_METADATA_EXTERNAL_ID);

        parent::tearDownAfterClass();
    }

    /**
     * Get a list of available asset types
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
     * Get a list of all assets (defaults to asset type images)
     */
    public function testListAllImages()
    {
        // The assets method defaults to fetching images if resource_type is omitted
        $result = self::$adminApi->assets([DeliveryType::KEY => DeliveryType::UPLOAD]);

        self::assertValidAsset($result['resources'][0], [AssetType::KEY => AssetType::IMAGE]);
    }

    /**
     * Get a list of all images with delivery type upload
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
     * Get a list of all images with delivery type upload and a given prefix
     */
    public function testListUploadedImagesWithPrefix()
    {
        $result = self::$adminApi->assets(
            [
                DeliveryType::KEY => DeliveryType::UPLOAD,
                'prefix'          => self::$UNIQUE_PREFIX,
            ]
        );

        self::assertCount(1, $result['resources']);
        self::assertValidAsset(
            $result['resources'][0],
            [
                'public_id'       => self::$PUBLIC_ID_TEST,
                DeliveryType::KEY => DeliveryType::UPLOAD,
                AssetType::KEY    => AssetType::IMAGE,
            ]
        );
    }

    /**
     * Get a list of all images with delivery type facebook
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
     * Facebook images assets do not contain height or width
     */
    public function testFacebookImagesWidthHeight()
    {
        $result = self::$adminApi->assets([DeliveryType::KEY => DeliveryType::FACEBOOK]);

        self::assertArrayNotHasKey('height', $result['resources'][0]);
        self::assertArrayNotHasKey('width', $result['resources'][0]);
    }

    /**
     * List raw uploaded files
     */
    public function testListRawUploadedFiles()
    {
        $result = self::$adminApi->assets([AssetType::KEY => AssetType::RAW]);

        self::assertValidAsset($result['resources'][0], [AssetType::KEY => AssetType::RAW]);
    }

    /**
     * Get a single uploaded asset with a given ID passed as a string
     */
    public function testListUploadedAssetsById()
    {
        $result = self::$adminApi->assetsByIds(self::$PUBLIC_ID_TEST);

        self::assertValidAsset(
            $result['resources'][0],
            [
                'public_id'    => self::$PUBLIC_ID_TEST,
                AssetType::KEY => AssetType::IMAGE,
            ]
        );
        self::assertCount(1, $result['resources']);
    }

    /**
     * Get uploaded assets matching the IDs passed as an array
     */
    public function testListUploadedAssetsByIds()
    {
        $result = self::$adminApi->assetsByIds([self::$PUBLIC_ID_TEST, self::$PUBLIC_ID_TEST_2]);

        self::assertValidAsset($result['resources'][0]);
        self::assertValidAsset($result['resources'][1]);
        self::assertCount(2, $result['resources']);
    }

    /**
     * Get images by tag
     */
    public function testListImagesByTag()
    {
        $result = self::$adminApi->assetsByTag(self::$UNIQUE_TEST_TAG_TO_ONE_IMAGE_ASSET, ['tags' => true]);

        self::assertValidAsset($result['resources'][0], [AssetType::KEY => AssetType::IMAGE]);
        self::assertContains(self::$UNIQUE_TEST_TAG_TO_ONE_IMAGE_ASSET, $result['resources'][0]['tags']);
        self::assertCount(1, $result['resources']);
    }

    /**
     * Get raw files by tag
     */
    public function testListRawFilesByTag()
    {
        $result = self::$adminApi->assetsByTag(
            self::$UNIQUE_TEST_TAG,
            [AssetType::KEY => AssetType::RAW, 'tags' => true]
        );

        self::assertValidAsset($result['resources'][0], [AssetType::KEY => AssetType::RAW]);
        self::assertContains(self::$UNIQUE_TEST_TAG, $result['resources'][0]['tags']);
        self::assertCount(2, $result['resources']);
    }

    /**
     * Get images by context key
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
     * Get video files by context key and value
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
     * Get images pending manual moderation
     */
    public function testListImagesPendingManualModeration()
    {
        $result = self::$adminApi->assetsByModeration(ModerationType::MANUAL, ModerationStatus::PENDING);

        self::assertValidAsset($result['resources'][0]);
        self::assertGreaterThanOrEqual(1, count($result['resources']));
    }

    /**
     * Get images automatically approved by the WebPurify add-on
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

    /**
     * Calling deleteAssetsByTag() without a resource_type defaults to deleting assets of type image
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
     * Restore deleted assets by public_id
     *
     * @throws ApiError
     */
    public function testRestoreDeletedAssetsByPublicId()
    {
        $result = self::$adminApi->deleteAssets([self::$RESTORE_PUBLIC_ID]);

        self::assertAssetDeleted($result, self::$RESTORE_PUBLIC_ID);

        $result = self::$adminApi->assetsByTag(self::$UNIQUE_TEST_TAG_RESTORE);

        self::assertEmpty($result['resources']);

        $result = self::$adminApi->restore(self::$RESTORE_PUBLIC_ID);

        self::assertEquals(AssetType::IMAGE, $result[self::$RESTORE_PUBLIC_ID][AssetType::KEY]);

        $result = self::$adminApi->assetsByTag(self::$UNIQUE_TEST_TAG_RESTORE);

        self::assertCount(1, $result['resources']);
    }

    /**
     * Restore two different deleted assets.
     *
     * @throws ApiError
     */
    public function testRestoreDifferentDeletedAssets()
    {
        $deleteResult = self::$adminApi->deleteAssets([self::$BACKUP_2_PUBLIC_ID, self::$BACKUP_3_PUBLIC_ID]);

        self::assertAssetDeleted($deleteResult, self::$BACKUP_2_PUBLIC_ID, 2);
        self::assertAssetDeleted($deleteResult, self::$BACKUP_3_PUBLIC_ID, 2);

        $secondAsset = self::$adminApi->asset(self::$BACKUP_2_PUBLIC_ID, ['versions' => true]);
        $thirdAsset  = self::$adminApi->asset(self::$BACKUP_3_PUBLIC_ID, ['versions' => true]);

        $restoreResult = self::$adminApi->restore(
            [
                self::$BACKUP_2_PUBLIC_ID,
                self::$BACKUP_3_PUBLIC_ID,
            ],
            [
                'versions' => [
                    $secondAsset['versions'][0]['version_id'],
                    $thirdAsset['versions'][0]['version_id'],
                ],
            ]
        );

        self::assertEquals($restoreResult[self::$BACKUP_2_PUBLIC_ID]['bytes'], self::$BACKUP_2_ASSET['bytes']);
        self::assertEquals($restoreResult[self::$BACKUP_3_PUBLIC_ID]['bytes'], self::$BACKUP_3_ASSET['bytes']);
    }

    /**
     * Gets asset backups
     */
    public function testBackupAsset()
    {
        $asset = self::$adminApi->asset(self::$RESTORE_PUBLIC_ID, ['versions' => true]);

        self::assertGreaterThanOrEqual(1, $asset['versions']);
    }

    /**
     * Delete uploaded images by a single public ID given as a string
     *
     * @throws ApiError
     */
    public function testDeleteSingleAssetByPublicId()
    {
        $result = self::$adminApi->deleteAssets(self::$DELETE_SINGLE_PUBLIC_ID);

        self::assertAssetDeleted($result, self::$DELETE_SINGLE_PUBLIC_ID);

        $this->expectException(NotFound::class);
        self::$adminApi->asset(self::$DELETE_SINGLE_PUBLIC_ID);
    }

    /**
     * Delete multiple uploaded images by public IDs given in an array
     *
     * @throws ApiError
     */
    public function testDeleteMultipleAssetsByPublicIds()
    {
        $result = self::$adminApi->deleteAssets(
            [
                self::$MULTI_DELETE_1_PUBLIC_ID,
                self::$MULTI_DELETE_2_PUBLIC_ID,
            ]
        );

        self::assertAssetDeleted($result, self::$MULTI_DELETE_1_PUBLIC_ID, 2);
        self::assertAssetDeleted($result, self::$MULTI_DELETE_2_PUBLIC_ID, 2);

        $this->expectException(NotFound::class);
        self::$adminApi->asset(self::$MULTI_DELETE_1_PUBLIC_ID);

        $this->expectException(NotFound::class);
        self::$adminApi->asset(self::$MULTI_DELETE_2_PUBLIC_ID);
    }

    /**
     * Delete uploaded images by public IDs with options
     *
     * @throws ApiError
     */
    public function testDeleteAssetsByPublicIdWithOptions()
    {
        $result = self::$adminApi->deleteAssets(
            [
                self::$MULTI_DELETE_OPTION_1_PUBLIC_ID,
                self::$MULTI_DELETE_OPTION_2_PUBLIC_ID,
                'nonexistent_id',
            ],
            [DeliveryType::KEY => DeliveryType::PRIVATE_DELIVERY]
        );

        self::assertAssetDeleted($result, self::$MULTI_DELETE_OPTION_2_PUBLIC_ID, 1, 1, 0, 2);

        $result = self::$adminApi->asset(self::$MULTI_DELETE_OPTION_1_PUBLIC_ID);
        self::assertValidAsset($result, [AssetType::KEY => AssetType::IMAGE]);

        $result = self::$adminApi->assetsByIds(
            self::$MULTI_DELETE_OPTION_2_PUBLIC_ID,
            [
                DeliveryType::KEY => DeliveryType::PRIVATE_DELIVERY,
            ]
        );

        self::assertCount(0, $result['resources']);
    }

    /**
     * Delete uploaded images by prefix
     *
     * @throws ApiError
     */
    public function testDeleteAssetsByPrefix()
    {
        $result = self::$adminApi->deleteAssetsByPrefix(self::$UNIQUE_DELETE_PREFIX);

        self::assertAssetDeleted($result, self::$UNIQUE_DELETE_PREFIX_PUBLIC_ID);

        $this->expectException(NotFound::class);
        self::$adminApi->asset(self::$UNIQUE_DELETE_PREFIX_PUBLIC_ID);
    }

    /**
     * Delete derived images only
     *
     * @throws ApiError
     */
    public function testDeleteDerivedImagesOnly()
    {
        $result = self::$uploadApi->explicit(
            self::$DELETE_DERIVED_IMAGE_PUBLIC_ID,
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

        $asset = self::$adminApi->asset(self::$DELETE_DERIVED_IMAGE_PUBLIC_ID);

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
            [self::$DELETE_DERIVED_IMAGE_PUBLIC_ID],
            [
                'keep_original' => true,
            ]
        );

        self::assertAssetDeleted($result, self::$DELETE_DERIVED_IMAGE_PUBLIC_ID, 1, 0, 1);

        $asset = self::$adminApi->asset(self::$DELETE_DERIVED_IMAGE_PUBLIC_ID);

        self::assertValidAsset($asset);
        self::assertEmpty($asset['derived']);
    }

    /**
     * Delete assets by options
     *
     * @throws ApiError
     */
    public function testDeleteAssetsByOptions()
    {
        if (! self::shouldRunDestructiveTests()) {
            self::markTestSkipped('Skipping DeleteAssetsByOptions test');
        }

        $asset = self::uploadTestAssetImage(
            [
                DeliveryType::KEY => DeliveryType::PRIVATE_DELIVERY,
                'tags'            => [self::$UNIQUE_TEST_TAG_DELETE_OPTIONS],
            ]
        );
        self::uploadTestAssetImage(
            [
                DeliveryType::KEY => DeliveryType::UPLOAD,
                'tags'            => [self::$UNIQUE_TEST_TAG_DELETE_OPTIONS],
            ]
        );
        $result = self::$adminApi->deleteAllAssets(
            [
                DeliveryType::KEY => DeliveryType::PRIVATE_DELIVERY,
            ]
        );

        self::assertAssetDeleted($result, $asset['public_id']);

        $assets = self::$adminApi->assetsByTag(self::$UNIQUE_TEST_TAG_DELETE_OPTIONS);

        self::assertCount(1, $assets['resources']);
        self::assertValidAsset($assets['resources'][0], [DeliveryType::KEY => DeliveryType::UPLOAD]);
    }

    /**
     * Request structured metadata to be returned in the response of the assets API response
     */
    public function testStructuredMetadataInAssets()
    {
        $result = self::$adminApi->assets(
            [
                "prefix"   => self::$PUBLIC_ID_TEST,
                "type"     => DeliveryType::UPLOAD,
                "metadata" => true,
            ]
        );

        foreach ($result['resources'] as $asset) {
            self::assertArrayHasKey('metadata', $asset);
        }

        $result = self::$adminApi->assets(
            [
                "prefix"   => self::$PUBLIC_ID_TEST,
                "type"     => DeliveryType::UPLOAD,
                "metadata" => false,
            ]
        );

        foreach ($result['resources'] as $asset) {
            self::assertArrayNotHasKey('metadata', $asset);
        }
    }

    /**
     * Request structured metadata to be returned in the response of the assets by tag API
     */
    public function testStructuredMetadataInAssetsByTag()
    {
        $result = self::$adminApi->assetsByTag(
            self::$UNIQUE_TEST_TAG_TO_ONE_IMAGE_ASSET,
            ["metadata" => true]
        );

        foreach ($result['resources'] as $asset) {
            self::assertArrayHasKey('metadata', $asset);
        }

        $result = self::$adminApi->assetsByTag(
            self::$UNIQUE_TEST_TAG_TO_ONE_IMAGE_ASSET,
            ["metadata" => false]
        );

        foreach ($result['resources'] as $asset) {
            self::assertArrayNotHasKey('metadata', $asset);
        }
    }

    /**
     * Request structured metadata to be returned in the response of the assets by context API
     */
    public function testStructuredMetadataInAssetsByContext()
    {
        $result = self::$adminApi->assetsByContext(
            self::$UNIQUE_CONTEXT_KEY,
            self::$UNIQUE_CONTEXT_VALUE,
            ["metadata" => true]
        );

        foreach ($result['resources'] as $asset) {
            self::assertArrayHasKey('metadata', $asset);
        }

        $result = self::$adminApi->assetsByContext(
            self::$UNIQUE_CONTEXT_KEY,
            self::$UNIQUE_CONTEXT_VALUE,
            ["metadata" => false]
        );

        foreach ($result['resources'] as $asset) {
            self::assertArrayNotHasKey('metadata', $asset);
        }
    }

    /**
     * Request structured metadata to be returned in the response of the assets by moderation API
     */
    public function testStructuredMetadataInAssetsByModerationApi()
    {
        $result = self::$adminApi->assetsByModeration(
            ModerationType::MANUAL,
            ModerationStatus::PENDING,
            ["metadata" => true]
        );

        foreach ($result['resources'] as $asset) {
            self::assertArrayHasKey('metadata', $asset);
        }

        $result = self::$adminApi->assetsByModeration(
            ModerationType::MANUAL,
            ModerationStatus::PENDING,
            ["metadata" => false]
        );

        foreach ($result['resources'] as $asset) {
            self::assertArrayNotHasKey('metadata', $asset);
        }
    }
}
