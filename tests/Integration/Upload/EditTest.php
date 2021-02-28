<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Integration\Upload;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Exception\BadRequest;
use Cloudinary\Api\Exception\NotFound;
use Cloudinary\Asset\AssetTransformation;
use Cloudinary\Test\Helpers\MockUploadApi;
use Cloudinary\Test\Helpers\RequestAssertionsTrait;
use Cloudinary\Test\Integration\IntegrationTestCase;
use Cloudinary\Test\Unit\Asset\AssetTestCase;
use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\Resize;

/**
 * Class EditTest
 */
final class EditTest extends IntegrationTestCase
{
    use RequestAssertionsTrait;

    const TRANSFORMATION_STRING = 'c_crop,g_face,h_400,w_400';

    private static $RENAME_SOURCE_PUBLIC_ID;
    private static $RENAME_TARGET_PUBLIC_ID;
    private static $RENAME_TO_EXISTING_SOURCE_PUBLIC_ID;
    private static $RENAME_TO_EXISTING_TARGET_PUBLIC_ID;
    private static $RENAME_TO_EXISTING_SOURCE_OVERWRITE_PUBLIC_ID;
    private static $RENAME_TO_EXISTING_TARGET_OVERWRITE_PUBLIC_ID;
    private static $CHANGE_TYPE_PUBLIC_ID;
    private static $DESTROY_PUBLIC_ID;
    private static $EXPLICIT_PUBLIC_ID;
    private static $TRANSFORMATION_OBJECT;

    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$RENAME_SOURCE_PUBLIC_ID                       =
            'upload_edit_rename_source_public_id_' . self::$UNIQUE_TEST_ID;
        self::$RENAME_TARGET_PUBLIC_ID                       =
            'upload_edit_rename_target__public_id_' . self::$UNIQUE_TEST_ID;
        self::$RENAME_TO_EXISTING_SOURCE_PUBLIC_ID           =
            'upload_edit_rename_to_existing_source_public_id_' . self::$UNIQUE_TEST_ID;
        self::$RENAME_TO_EXISTING_TARGET_PUBLIC_ID           =
            'upload_edit_rename_to_existing_target_public_id_' . self::$UNIQUE_TEST_ID;
        self::$RENAME_TO_EXISTING_SOURCE_OVERWRITE_PUBLIC_ID =
            'upload_edit_rename_to_existing_overwrite_source_public_id_' . self::$UNIQUE_TEST_ID;
        self::$RENAME_TO_EXISTING_TARGET_OVERWRITE_PUBLIC_ID =
            'upload_edit_rename_to_existing_overwrite_target_public_id_' . self::$UNIQUE_TEST_ID;
        self::$CHANGE_TYPE_PUBLIC_ID                         =
            'upload_edit_change_type_public_id_' . self::$UNIQUE_TEST_ID;
        self::$DESTROY_PUBLIC_ID                             = 'upload_edit_destroy_public_id_' . self::$UNIQUE_TEST_ID;
        self::$EXPLICIT_PUBLIC_ID                            =
            'upload_edit_explicit_public_id_' . self::$UNIQUE_TEST_ID;
        self::$TRANSFORMATION_OBJECT                         = (new AssetTransformation())->resize(
            Resize::crop(400, 400, Gravity::face())
        );

        self::uploadTestAssetImage(['public_id' => self::$EXPLICIT_PUBLIC_ID]);
        self::uploadTestAssetImage(['public_id' => self::$DESTROY_PUBLIC_ID]);
        self::uploadTestAssetImage(['public_id' => self::$RENAME_SOURCE_PUBLIC_ID]);
        self::uploadTestAssetImage(['public_id' => self::$RENAME_TO_EXISTING_SOURCE_PUBLIC_ID]);
        self::uploadTestAssetImage(['public_id' => self::$RENAME_TO_EXISTING_TARGET_PUBLIC_ID]);
        self::uploadTestAssetImage(['public_id' => self::$RENAME_TO_EXISTING_SOURCE_OVERWRITE_PUBLIC_ID]);
        self::uploadTestAssetImage(['public_id' => self::$RENAME_TO_EXISTING_TARGET_OVERWRITE_PUBLIC_ID]);
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestAssets();

        parent::tearDownAfterClass();
    }

    /**
     * Deleting an image by Public ID
     */
    public function testDestroyImageByPublicID()
    {
        $result = self::$uploadApi->destroy(self::$DESTROY_PUBLIC_ID);

        self::assertEquals('ok', $result['result']);

        $this->expectException(NotFound::class);
        self::$adminApi->asset(self::$DESTROY_PUBLIC_ID);
    }

    /**
     * Rename an image
     */
    public function testRenameImage()
    {
        $asset = self::$uploadApi->rename(self::$RENAME_SOURCE_PUBLIC_ID, self::$RENAME_TARGET_PUBLIC_ID);

        self::assertValidAsset($asset, ['public_id' => self::$RENAME_TARGET_PUBLIC_ID]);

        $asset = self::$adminApi->asset(self::$RENAME_TARGET_PUBLIC_ID);

        self::assertValidAsset($asset, ['public_id' => self::$RENAME_TARGET_PUBLIC_ID]);
    }

    /**
     * Try to rename an image to an existing image public id
     */
    public function testRenameToExistingImagePublicId()
    {
        $this->expectException(BadRequest::class);
        self::$uploadApi->rename(
            self::$RENAME_TO_EXISTING_SOURCE_PUBLIC_ID,
            self::$RENAME_TO_EXISTING_TARGET_PUBLIC_ID
        );
    }

    /**
     * Rename an image to an existing image public id, overwriting existing images
     */
    public function testRenameAndOverwriteToExistingImagePublicId()
    {
        $resource = self::$uploadApi->rename(
            self::$RENAME_TO_EXISTING_SOURCE_OVERWRITE_PUBLIC_ID,
            self::$RENAME_TO_EXISTING_TARGET_OVERWRITE_PUBLIC_ID,
            ['overwrite' => true]
        );

        self::assertValidAsset($resource, ['public_id' => self::$RENAME_TO_EXISTING_TARGET_OVERWRITE_PUBLIC_ID]);

        $resource = self::$adminApi->asset(self::$RENAME_TO_EXISTING_TARGET_OVERWRITE_PUBLIC_ID);

        self::assertValidAsset($resource, ['public_id' => self::$RENAME_TO_EXISTING_TARGET_OVERWRITE_PUBLIC_ID]);
    }

    /**
     * Change an image type
     */
    public function testChangeImageType()
    {
        $mockUploadApi = new MockUploadApi();
        $mockUploadApi->rename(
            self::$CHANGE_TYPE_PUBLIC_ID,
            self::$CHANGE_TYPE_PUBLIC_ID,
            ['to_type' => 'private']
        );
        $lastRequest = $mockUploadApi->getMockHandler()->getLastRequest();

        self::assertRequestUrl($lastRequest, '/image/rename');
        self::assertRequestPost($lastRequest);
        self::assertRequestBodySubset(
            $lastRequest,
            [
                'to_type' => 'private',
                'from_public_id' => self::$CHANGE_TYPE_PUBLIC_ID,
                'to_public_id' => self::$CHANGE_TYPE_PUBLIC_ID
            ]
        );
    }

    /**
     * Performs an eager transformation on a previously uploaded image
     */
    public function testEagerTransformation()
    {
        $asset = self::$uploadApi->explicit(
            self::$EXPLICIT_PUBLIC_ID,
            [
                'type'  => 'upload',
                'eager' => [
                    self::$TRANSFORMATION_OBJECT,
                ],
            ]
        );

        self::assertValidAsset($asset);
        self::assertCount(1, $asset['eager']);
        self::assertValidTransformationRepresentation(
            $asset['eager'][0],
            [
                'transformation' => self::TRANSFORMATION_STRING,
                'format'         => AssetTestCase::IMG_EXT_GIF,
                'bytes'          => 43,
                'width'          => 1,
                'height'         => 1,
            ]
        );

        $asset = self::$adminApi->asset($asset['public_id']);

        self::assertCount(1, $asset['derived']);
        self::assertValidDerivedAsset(
            $asset['derived'][0],
            [
                'transformation' => self::TRANSFORMATION_STRING,
                'format'         => AssetTestCase::IMG_EXT_GIF,
                'bytes'          => 43,
            ]
        );
    }
}
