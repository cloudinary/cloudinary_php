<?php

namespace Cloudinary\Test\Integration\Upload;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Exception\NotFound;
use Cloudinary\Asset\AssetTransformation;
use Cloudinary\Test\Integration\IntegrationTestCase;
use Cloudinary\Test\Unit\Asset\AssetTestCase;
use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\Resize;

/**
 * Class EditTest
 */
final class EditTest extends IntegrationTestCase
{
    const TRANSFORMATION_STRING = 'c_crop,g_face,h_400,w_400';

    private static $SOURCE_PUBLIC_ID;
    private static $RENAME_PUBLIC_ID;
    private static $DESTROY_PUBLIC_ID;
    private static $EXPLICIT_PUBLIC_ID;
    private static $TRANSFORMATION_OBJECT;

    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$SOURCE_PUBLIC_ID = 'upload_edit_source_public_id_' . self::$UNIQUE_TEST_ID;
        self::$RENAME_PUBLIC_ID = 'upload_edit_rename_public_id_' . self::$UNIQUE_TEST_ID;
        self::$DESTROY_PUBLIC_ID = 'upload_edit_destroy_public_id_' . self::$UNIQUE_TEST_ID;
        self::$EXPLICIT_PUBLIC_ID = 'upload_edit_explicit_public_id_' . self::$UNIQUE_TEST_ID;
        self::$TRANSFORMATION_OBJECT = (new AssetTransformation())->resize(Resize::crop(400, 400, Gravity::face()));

        self::uploadTestResourceImage(['public_id' => self::$EXPLICIT_PUBLIC_ID]);
        self::uploadTestResourceImage(['public_id' => self::$DESTROY_PUBLIC_ID]);
        self::uploadTestResourceImage(['public_id' => self::$SOURCE_PUBLIC_ID]);
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestResources();

        parent::tearDownAfterClass();
    }

    /**
     * Deleting an image by Public ID
     */
    public function testDestroyImageByPublicID()
    {
        $result = self::$uploadApi->destroy(self::$DESTROY_PUBLIC_ID);

        $this->assertEquals('ok', $result['result']);

        $this->expectException(NotFound::class);
        self::$adminApi->resource(self::$DESTROY_PUBLIC_ID);
    }

    /**
     * Rename an image
     */
    public function testRenameImage()
    {
        $resource = self::$uploadApi->rename(self::$SOURCE_PUBLIC_ID, self::$RENAME_PUBLIC_ID);

        self::assertValidResource($resource, ['public_id' => self::$RENAME_PUBLIC_ID]);

        $resource = self::$adminApi->resource(self::$RENAME_PUBLIC_ID);

        self::assertValidResource($resource, ['public_id' => self::$RENAME_PUBLIC_ID]);
    }

    /**
     * Performs an eager transformation on a previously uploaded image
     */
    public function testEagerTransformation()
    {
        $resource = self::$uploadApi->explicit(
            self::$EXPLICIT_PUBLIC_ID,
            [
                'type' => 'upload',
                'eager' => [
                    self::$TRANSFORMATION_OBJECT,
                ],
            ]
        );

        self::assertValidResource($resource);
        self::assertCount(1, $resource['eager']);
        self::assertValidTransformationRepresentation(
            $resource['eager'][0],
            [
                'transformation' => self::TRANSFORMATION_STRING,
                'format' => AssetTestCase::IMG_EXT_GIF,
                'bytes' => 43,
                'width' => 1,
                'height' => 1
            ]
        );

        $resource = self::$adminApi->resource($resource['public_id']);

        self::assertCount(1, $resource['derived']);
        self::assertValidDerivedResource(
            $resource['derived'][0],
            [
                'transformation' => self::TRANSFORMATION_STRING,
                'format' => AssetTestCase::IMG_EXT_GIF,
                'bytes' => 43,
            ]
        );
    }
}
