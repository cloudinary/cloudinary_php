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
use Cloudinary\Asset\AssetType;
use Cloudinary\Asset\DeliveryType;
use Cloudinary\Asset\ModerationStatus;
use Cloudinary\Asset\ModerationType;
use Cloudinary\Test\Integration\IntegrationTestCase;
use Cloudinary\Test\Unit\Asset\AssetTestCase;
use Exception;

/**
 * Class ResourcesTest
 */
final class ResourcesTest extends IntegrationTestCase
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
    private static $UNIQUE_PREFIX;

    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$UNIQUE_PREFIX                      = 'resources_prefix_' . self::$UNIQUE_TEST_ID;
        self::$PUBLIC_ID_TEST                     = self::$UNIQUE_PREFIX . '_public_id';
        self::$PUBLIC_ID_TEST_2                   = 'resources_public_id_2' . self::$UNIQUE_TEST_ID;
        self::$DELETE_DERIVED_IMAGE_PUBLIC_ID     = 'resources_delete_derived_public_id_' . self::$UNIQUE_TEST_ID;
        self::$MULTI_DELETE_1_PUBLIC_ID           = 'resources_multi_delete_1_public_id_' . self::$UNIQUE_TEST_ID;
        self::$MULTI_DELETE_2_PUBLIC_ID           = 'resources_multi_delete_2_public_id_' . self::$UNIQUE_TEST_ID;
        self::$MULTI_DELETE_OPTION_1_PUBLIC_ID    = 'resources_multi_delete_options_1_public_id_' .
                                                    self::$UNIQUE_TEST_ID;
        self::$MULTI_DELETE_OPTION_2_PUBLIC_ID    = 'resources_multi_delete_options_2_public_id_' .
                                                    self::$UNIQUE_TEST_ID;
        self::$DELETE_SINGLE_PUBLIC_ID            = 'resources_delete_single_public_id_' . self::$UNIQUE_TEST_ID;
        self::$RESTORE_PUBLIC_ID                  = 'resources_restore_public_id_' . self::$UNIQUE_TEST_ID;
        self::$UNIQUE_CONTEXT_KEY                 = 'resources_context_key_' . self::$UNIQUE_TEST_ID;
        self::$UNIQUE_CONTEXT_VALUE               = 'resources_context_value_' . self::$UNIQUE_TEST_ID;
        self::$UNIQUE_CONTEXT                     = self::$UNIQUE_CONTEXT_KEY . '=' . self::$UNIQUE_CONTEXT_VALUE;
        self::$UNIQUE_TEST_TAG_DELETE             = 'resources_delete_' . self::$UNIQUE_TEST_TAG;
        self::$UNIQUE_TEST_TAG_DELETE_OPTIONS     = 'resources_delete_by_options_' . self::$UNIQUE_TEST_TAG;
        self::$UNIQUE_TEST_TAG_RESTORE            = 'resources_restore_' . self::$UNIQUE_TEST_TAG;
        self::$UNIQUE_TEST_TAG_TO_ONE_IMAGE_ASSET = 'resources_unique_tag_to_one_image_asset_' . self::$UNIQUE_TEST_TAG;
        self::$UNIQUE_DELETE_PREFIX               = 'resources_delete_by_prefix_' . self::$UNIQUE_TEST_ID;
        self::$UNIQUE_DELETE_PREFIX_PUBLIC_ID     = self::$UNIQUE_DELETE_PREFIX . '_public_id';

        self::uploadTestResourceImage(
            [
                'tags'              => [self::$UNIQUE_TEST_TAG_TO_ONE_IMAGE_ASSET],
                'context'           => self::$UNIQUE_CONTEXT,
                ModerationType::KEY => ModerationType::MANUAL,
                'public_id'         => self::$PUBLIC_ID_TEST,
            ]
        );
        self::uploadTestResourceImage(
            [
                'public_id' => self::$RESTORE_PUBLIC_ID,
                'tags'      => [self::$UNIQUE_TEST_TAG_RESTORE],
                'backup'    => true,
            ]
        );
        self::uploadTestResourceImage(['public_id' => self::$MULTI_DELETE_OPTION_1_PUBLIC_ID]);
        self::uploadTestResourceImage(
            [
                DeliveryType::KEY => DeliveryType::PRIVATE_DELIVERY,
                'public_id'       => self::$MULTI_DELETE_OPTION_2_PUBLIC_ID,
            ]
        );
        self::uploadTestResourceImage(['public_id' => self::$DELETE_DERIVED_IMAGE_PUBLIC_ID]);
        self::uploadTestResourceImage(['public_id' => self::$UNIQUE_DELETE_PREFIX_PUBLIC_ID]);
        self::uploadTestResourceImage(['public_id' => self::$PUBLIC_ID_TEST_2]);
        self::uploadTestResourceImage(['public_id' => self::$MULTI_DELETE_1_PUBLIC_ID]);
        self::uploadTestResourceImage(['public_id' => self::$MULTI_DELETE_2_PUBLIC_ID]);
        self::uploadTestResourceImage(['public_id' => self::$DELETE_SINGLE_PUBLIC_ID]);
        self::uploadTestResourceFile(['context' => self::$UNIQUE_CONTEXT]);
        self::uploadTestResourceVideo(['context' => self::$UNIQUE_CONTEXT]);
        self::uploadTestResourceImage(['tags' => [self::$UNIQUE_TEST_TAG_DELETE]]);
        self::uploadTestResourceFile(['tags' => [self::$UNIQUE_TEST_TAG_DELETE]]);
        self::uploadTestResourceVideo(['tags' => [self::$UNIQUE_TEST_TAG_DELETE]]);
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
        self::$adminApi->deleteResourcesByTag(self::$UNIQUE_TEST_TAG, [AssetType::KEY => AssetType::IMAGE]);
        self::$adminApi->deleteResourcesByTag(self::$UNIQUE_TEST_TAG, [AssetType::KEY => AssetType::RAW]);
        self::$adminApi->deleteResourcesByTag(self::$UNIQUE_TEST_TAG, [AssetType::KEY => AssetType::VIDEO]);

        parent::tearDownAfterClass();
    }

    /**
     * Get a list of available resource types
     */
    public function testListResourceTypes()
    {
        $result = self::$adminApi->resourceTypes();

        $this->assertNotEmpty($result['resource_types']);
        $this->assertContains(AssetType::IMAGE, $result['resource_types']);
        $this->assertContains(AssetType::RAW, $result['resource_types']);
        $this->assertContains(AssetType::VIDEO, $result['resource_types']);
    }

    /**
     * Get a list of all resources (defaults to asset type images)
     */
    public function testListAllImages()
    {
        // The resources method defaults to fetching images if resource_type is omitted
        $result = self::$adminApi->resources([DeliveryType::KEY => DeliveryType::UPLOAD]);

        self::assertValidResource($result['resources'][0], [AssetType::KEY => AssetType::IMAGE]);
    }

    /**
     * Get a list of all images with delivery type upload
     */
    public function testListUploadedImages()
    {
        $result = self::$adminApi->resources([DeliveryType::KEY => DeliveryType::UPLOAD]);

        self::assertValidResource(
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
        $result = self::$adminApi->resources(
            [
                DeliveryType::KEY => DeliveryType::UPLOAD,
                'prefix'          => self::$UNIQUE_PREFIX,
            ]
        );

        $this->assertCount(1, $result['resources']);
        self::assertValidResource(
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
        $result = self::$adminApi->resources([DeliveryType::KEY => DeliveryType::FACEBOOK]);

        self::assertValidResource(
            $result['resources'][0],
            [
                DeliveryType::KEY => DeliveryType::FACEBOOK,
                AssetType::KEY    => AssetType::IMAGE,
            ]
        );
    }

    /**
     * Facebook images resources do not contain height or width
     */
    public function testFacebookImagesWidthHeight()
    {
        $result = self::$adminApi->resources([DeliveryType::KEY => DeliveryType::FACEBOOK]);

        $this->assertArrayNotHasKey('height', $result['resources'][0]);
        $this->assertArrayNotHasKey('width', $result['resources'][0]);
    }

    /**
     * List raw uploaded files
     */
    public function testListRawUploadedFiles()
    {
        $result = self::$adminApi->resources([AssetType::KEY => AssetType::RAW]);

        self::assertValidResource($result['resources'][0], [AssetType::KEY => AssetType::RAW]);
    }

    /**
     * Get a single uploaded resource with a given ID passed as a string
     */
    public function testListUploadedResourcesById()
    {
        $result = self::$adminApi->resourcesByIds(self::$PUBLIC_ID_TEST);

        self::assertValidResource(
            $result['resources'][0],
            [
                'public_id'    => self::$PUBLIC_ID_TEST,
                AssetType::KEY => AssetType::IMAGE,
            ]
        );
        $this->assertCount(1, $result['resources']);
    }

    /**
     * Get uploaded resources matching the IDs passed as an array
     */
    public function testListUploadedResourcesByIds()
    {
        $result = self::$adminApi->resourcesByIds([self::$PUBLIC_ID_TEST, self::$PUBLIC_ID_TEST_2]);

        self::assertValidResource($result['resources'][0]);
        self::assertValidResource($result['resources'][1]);
        $this->assertCount(2, $result['resources']);
    }

    /**
     * Get images by tag
     */
    public function testListImagesByTag()
    {
        $result = self::$adminApi->resourcesByTag(self::$UNIQUE_TEST_TAG_TO_ONE_IMAGE_ASSET, ['tags' => true]);

        self::assertValidResource($result['resources'][0], [AssetType::KEY => AssetType::IMAGE]);
        $this->assertContains(self::$UNIQUE_TEST_TAG_TO_ONE_IMAGE_ASSET, $result['resources'][0]['tags']);
        $this->assertCount(1, $result['resources']);
    }

    /**
     * Get raw files by tag
     */
    public function testListRawFilesByTag()
    {
        $result = self::$adminApi->resourcesByTag(
            self::$UNIQUE_TEST_TAG,
            [AssetType::KEY => AssetType::RAW, 'tags' => true]
        );

        self::assertValidResource($result['resources'][0], [AssetType::KEY => AssetType::RAW]);
        $this->assertContains(self::$UNIQUE_TEST_TAG, $result['resources'][0]['tags']);
        $this->assertCount(2, $result['resources']);
    }

    /**
     * Get images by context key
     */
    public function testListImagesByContextKey()
    {
        $result = self::$adminApi->resourcesByContext(self::$UNIQUE_CONTEXT_KEY);

        self::assertValidResource($result['resources'][0]);
        $this->assertCount(1, $result['resources']);
        $this->assertCount(1, $result['resources'][0]['context']['custom']);
        $this->assertEquals(
            self::$UNIQUE_CONTEXT_VALUE,
            $result['resources'][0]['context']['custom'][self::$UNIQUE_CONTEXT_KEY]
        );
    }

    /**
     * Get video files by context key and value
     */
    public function testListVideosByContextKeyValue()
    {
        $result = self::$adminApi->resourcesByContext(
            self::$UNIQUE_CONTEXT_KEY,
            self::$UNIQUE_CONTEXT_VALUE,
            [AssetType::KEY => AssetType::VIDEO]
        );

        self::assertValidResource($result['resources'][0], [AssetType::KEY => AssetType::VIDEO]);
        $this->assertCount(1, $result['resources']);
        $this->assertCount(1, $result['resources'][0]['context']['custom']);
        $this->assertEquals(
            self::$UNIQUE_CONTEXT_VALUE,
            $result['resources'][0]['context']['custom'][self::$UNIQUE_CONTEXT_KEY]
        );
    }

    /**
     * Get images pending manual moderation
     */
    public function testListImagesPendingManualModeration()
    {
        $result = self::$adminApi->resourcesByModeration(ModerationType::MANUAL, ModerationStatus::PENDING);

        self::assertValidResource($result['resources'][0]);
        $this->assertGreaterThanOrEqual(1, count($result['resources']));
    }

    /**
     * Get images automatically approved by the WebPurify add-on
     *
     * @throws ApiError
     * @throws Exception
     */
    public function testListImagesApprovedByWebPurify()
    {
        if (! self::shouldTestAddOn('webpurify')) {
            $this->markTestSkipped('Skipping WebPurify test');
        }

        self::uploadTestResourceImage([ModerationType::KEY => ModerationType::WEBPURIFY]);

        $this->retryAssertionIfThrows(
            function () {
                $result = self::$adminApi->resourcesByModeration(
                    ModerationType::WEBPURIFY,
                    ModerationStatus::APPROVED
                );
                $this->assertGreaterThanOrEqual(1, count($result['resources']));
                $this->assertEquals(AssetType::IMAGE, $result['resources'][0][AssetType::KEY]);
            },
            3,
            1,
            'Unable to list images approved by WebPurify'
        );
    }

    /**
     * Calling deleteResourcesByTag() without a resource_type defaults to deleting resources of type image
     *
     * @throws ApiError
     */
    public function testDeleteResourcesByTagDefaultsToImage()
    {
        $result = self::$adminApi->resourcesByTag(self::$UNIQUE_TEST_TAG_DELETE, [AssetType::KEY => AssetType::IMAGE]);

        $this->assertEquals(AssetType::IMAGE, $result['resources'][0][AssetType::KEY]);

        $resultDeleting = self::$adminApi->deleteResourcesByTag(self::$UNIQUE_TEST_TAG_DELETE);

        self::assertResourceDeleted($resultDeleting, $result['resources'][0]['public_id']);

        $result = self::$adminApi->resourcesByTag(self::$UNIQUE_TEST_TAG_DELETE, [AssetType::KEY => AssetType::IMAGE]);

        $this->assertEmpty($result['resources']);

        $result = self::$adminApi->resourcesByTag(self::$UNIQUE_TEST_TAG_DELETE, [AssetType::KEY => AssetType::RAW]);

        $this->assertCount(1, $result['resources']);

        $result = self::$adminApi->resourcesByTag(self::$UNIQUE_TEST_TAG_DELETE, [AssetType::KEY => AssetType::VIDEO]);

        $this->assertCount(1, $result['resources']);
    }

    /**
     * Restore deleted resources by public_id
     *
     * @throws ApiError
     */
    public function testRestoreDeletedResourcesByPublicId()
    {
        $result = self::$adminApi->deleteResources([self::$RESTORE_PUBLIC_ID]);

        self::assertResourceDeleted($result, self::$RESTORE_PUBLIC_ID);

        $result = self::$adminApi->resourcesByTag(self::$UNIQUE_TEST_TAG_RESTORE);

        $this->assertEmpty($result['resources']);

        $result = self::$adminApi->restore(self::$RESTORE_PUBLIC_ID);

        $this->assertEquals(AssetType::IMAGE, $result[self::$RESTORE_PUBLIC_ID][AssetType::KEY]);

        $result = self::$adminApi->resourcesByTag(self::$UNIQUE_TEST_TAG_RESTORE);

        $this->assertCount(1, $result['resources']);
    }

    /**
     * Update the access mode of uploaded images by public IDs
     */
    public function testUpdateResourcesAccessModeByPublicId()
    {
        $this->markTestIncomplete('updateResourcesAccessMode not implemented yet');
    }

    /**
     * Update the access mode of uploaded images by prefix
     */
    public function testUpdateResourcesAccessModeByPrefix()
    {
        $this->markTestIncomplete('updateResourcesAccessMode not implemented yet');
    }

    /**
     * Update the access mode of uploaded images by tag
     */
    public function testUpdateResourcesAccessModeByTag()
    {
        $this->markTestIncomplete('updateResourcesAccessMode not implemented yet');
    }

    /**
     * Delete uploaded images by a single public ID given as a string
     *
     * @throws ApiError
     */
    public function testDeleteSingleResourceByPublicId()
    {
        $result = self::$adminApi->deleteResources(self::$DELETE_SINGLE_PUBLIC_ID);

        self::assertResourceDeleted($result, self::$DELETE_SINGLE_PUBLIC_ID);

        $this->expectException(NotFound::class);
        self::$adminApi->resource(self::$DELETE_SINGLE_PUBLIC_ID);
    }

    /**
     * Delete multiple uploaded images by public IDs given in an array
     *
     * @throws ApiError
     */
    public function testDeleteMultipleResourcesByPublicIds()
    {
        $result = self::$adminApi->deleteResources(
            [
                self::$MULTI_DELETE_1_PUBLIC_ID,
                self::$MULTI_DELETE_2_PUBLIC_ID,
            ]
        );

        self::assertResourceDeleted($result, self::$MULTI_DELETE_1_PUBLIC_ID, 2);
        self::assertResourceDeleted($result, self::$MULTI_DELETE_2_PUBLIC_ID, 2);

        $this->expectException(NotFound::class);
        self::$adminApi->resource(self::$MULTI_DELETE_1_PUBLIC_ID);

        $this->expectException(NotFound::class);
        self::$adminApi->resource(self::$MULTI_DELETE_2_PUBLIC_ID);
    }

    /**
     * Delete uploaded images by public IDs with options
     *
     * @throws ApiError
     */
    public function testDeleteResourcesByPublicIdWithOptions()
    {
        $result = self::$adminApi->deleteResources(
            [
                self::$MULTI_DELETE_OPTION_1_PUBLIC_ID,
                self::$MULTI_DELETE_OPTION_2_PUBLIC_ID,
                'nonexistent_id',
            ],
            [DeliveryType::KEY => DeliveryType::PRIVATE_DELIVERY]
        );

        self::assertResourceDeleted($result, self::$MULTI_DELETE_OPTION_2_PUBLIC_ID, 1, 1, 0, 2);

        $result = self::$adminApi->resource(self::$MULTI_DELETE_OPTION_1_PUBLIC_ID);
        self::assertValidResource($result, [AssetType::KEY => AssetType::IMAGE]);

        $result = self::$adminApi->resourcesByIds(
            self::$MULTI_DELETE_OPTION_2_PUBLIC_ID,
            [
                DeliveryType::KEY => DeliveryType::PRIVATE_DELIVERY,
            ]
        );

        $this->assertCount(0, $result['resources']);
    }

    /**
     * Delete uploaded images by prefix
     *
     * @throws ApiError
     */
    public function testDeleteResourcesByPrefix()
    {
        $result = self::$adminApi->deleteResourcesByPrefix(self::$UNIQUE_DELETE_PREFIX);

        self::assertResourceDeleted($result, self::$UNIQUE_DELETE_PREFIX_PUBLIC_ID);

        $this->expectException(NotFound::class);
        self::$adminApi->resource(self::$UNIQUE_DELETE_PREFIX_PUBLIC_ID);
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

        $resource = self::$adminApi->resource(self::$DELETE_DERIVED_IMAGE_PUBLIC_ID);

        self::assertCount(1, $resource['derived']);
        self::assertValidDerivedResource(
            $resource['derived'][0],
            [
                'transformation' => self::TRANSFORMATION_AS_STRING,
                'format'         => AssetTestCase::IMG_EXT_GIF,
                'bytes'          => 43,
            ]
        );

        $result = self::$adminApi->deleteResources(
            [self::$DELETE_DERIVED_IMAGE_PUBLIC_ID],
            [
                'keep_original' => true,
            ]
        );

        self::assertResourceDeleted($result, self::$DELETE_DERIVED_IMAGE_PUBLIC_ID, 1, 0, 1);

        $resource = self::$adminApi->resource(self::$DELETE_DERIVED_IMAGE_PUBLIC_ID);

        self::assertValidResource($resource);
        $this->assertEmpty($resource['derived']);
    }

    /**
     * Delete resources by options
     *
     * @throws ApiError
     */
    public function testDeleteResourcesByOptions()
    {
        if (! self::shouldRunDestructiveTests()) {
            self::markTestSkipped('Skipping DeleteResourcesByOptions test');
        }

        $resource = self::uploadTestResourceImage(
            [
                DeliveryType::KEY => DeliveryType::PRIVATE_DELIVERY,
                'tags'            => [self::$UNIQUE_TEST_TAG_DELETE_OPTIONS],
            ]
        );
        self::uploadTestResourceImage(
            [
                DeliveryType::KEY => DeliveryType::UPLOAD,
                'tags'            => [self::$UNIQUE_TEST_TAG_DELETE_OPTIONS],
            ]
        );
        $result = self::$adminApi->deleteAllResources(
            [
                DeliveryType::KEY => DeliveryType::PRIVATE_DELIVERY,
            ]
        );

        self::assertResourceDeleted($result, $resource['public_id']);

        $resources = self::$adminApi->resourcesByTag(self::$UNIQUE_TEST_TAG_DELETE_OPTIONS);

        $this->assertCount(1, $resources['resources']);
        self::assertValidResource($resources['resources'][0], [DeliveryType::KEY => DeliveryType::UPLOAD]);
    }
}
