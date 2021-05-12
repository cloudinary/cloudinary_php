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
use Cloudinary\Api\Metadata\StringMetadataField;
use Cloudinary\Asset\AssetTransformation;
use Cloudinary\Asset\AssetType;
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

    const RENAME_TARGET                       = 'rename_target';
    const RENAME_SOURCE                       = 'rename_source';
    const RENAME_TO_EXISTING_SOURCE           = 'rename_to_existing_source';
    const RENAME_TO_EXISTING_TARGET           = 'rename_to_existing_target';
    const RENAME_TO_EXISTING_SOURCE_OVERWRITE = 'rename_to_existing_overwrite_source';
    const RENAME_TO_EXISTING_TARGET_OVERWRITE = 'rename_to_existing_overwrite_target';
    const RENAME_TARGET_FOR_CONTEXT_TEST      = 'rename_target_for_context_test';
    const RENAME_SOURCE_FOR_CONTEXT_TEST      = 'rename_source_for_context_test';
    const RENAME_TARGET_FOR_METADATA_TEST     = 'rename_target_for_metadata_test';
    const RENAME_SOURCE_FOR_METADATA_TEST     = 'rename_source_for_metadata_test';
    const CHANGE_TYPE                         = 'change_type';
    const DESTROY                             = 'destroy';
    const EXPLICIT                            = 'explicit';
    const EXCEPTION_IMAGE                     = 'exception_image';
    const EXCEPTION_RAW                       = 'exception_raw';

    private static $TRANSFORMATION_OBJECT;
    private static $RENAME_RETURN_CONTEXT_KEY;
    private static $RENAME_RETURN_CONTEXT_VALUE;
    private static $RENAME_RETURN_METADATA;
    private static $RENAME_RETURN_METADATA_EXTERNAL_ID;
    private static $RENAME_RETURN_METADATA_DEFAULT_VALUE;

    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$TRANSFORMATION_OBJECT                = (new AssetTransformation())->resize(
            Resize::crop(400, 400, Gravity::face())
        );
        self::$RENAME_RETURN_CONTEXT_KEY            = 'rename_return_context_key_' . self::$UNIQUE_TEST_ID;
        self::$RENAME_RETURN_CONTEXT_VALUE          = 'rename_return_context_value_' . self::$UNIQUE_TEST_ID;
        self::$RENAME_RETURN_METADATA_EXTERNAL_ID   = 'rename_metadata_external_id_' . self::$UNIQUE_TEST_ID;
        self::$RENAME_RETURN_METADATA_DEFAULT_VALUE = 'rename_metadata_default_value_' . self::$UNIQUE_TEST_ID;

        self::$RENAME_RETURN_METADATA = new StringMetadataField(self::$RENAME_RETURN_METADATA_EXTERNAL_ID);
        self::$RENAME_RETURN_METADATA->setExternalId(self::$RENAME_RETURN_METADATA_EXTERNAL_ID);
        self::$RENAME_RETURN_METADATA->setDefaultValue(self::$RENAME_RETURN_METADATA_DEFAULT_VALUE);
        self::$RENAME_RETURN_METADATA->setMandatory(true);
        self::$adminApi->addMetadataField(self::$RENAME_RETURN_METADATA);

        self::createTestAssets(
            [
                self::RENAME_TARGET                   => ['upload' => false],
                self::RENAME_SOURCE,
                self::RENAME_TO_EXISTING_SOURCE,
                self::RENAME_TO_EXISTING_TARGET,
                self::RENAME_TO_EXISTING_SOURCE_OVERWRITE,
                self::RENAME_TO_EXISTING_TARGET_OVERWRITE,
                self::RENAME_TARGET_FOR_CONTEXT_TEST  => ['upload' => false],
                self::RENAME_SOURCE_FOR_CONTEXT_TEST  => [
                    'options' => [
                        'context' => [
                            self::$RENAME_RETURN_CONTEXT_KEY => self::$RENAME_RETURN_CONTEXT_VALUE,
                        ],
                    ],
                ],
                self::RENAME_TARGET_FOR_METADATA_TEST => ['upload' => false],
                self::RENAME_SOURCE_FOR_METADATA_TEST,
                self::CHANGE_TYPE                     => ['upload' => false],
                self::DESTROY,
                self::EXPLICIT,
                self::EXCEPTION_IMAGE,
                self::EXCEPTION_RAW                   => [
                    'options' => [AssetType::KEY => AssetType::RAW],
                    'cleanup' => true,
                ],
            ]
        );
    }

    /**
     * @throws ApiError
     */
    public static function tearDownAfterClass()
    {
        self::cleanupTestAssets();
        self::$adminApi->deleteMetadataField(self::$RENAME_RETURN_METADATA_EXTERNAL_ID);

        parent::tearDownAfterClass();
    }

    /**
     * Deleting an image by Public ID
     */
    public function testDestroyImageByPublicID()
    {
        $result = self::$uploadApi->destroy(self::getTestAssetPublicId(self::DESTROY));

        self::assertEquals('ok', $result['result']);

        $this->expectException(NotFound::class);
        self::$adminApi->asset(self::getTestAssetPublicId(self::DESTROY));
    }

    /**
     * Rename an image
     */
    public function testRenameImage()
    {
        $asset = self::$uploadApi->rename(
            self::getTestAssetPublicId(self::RENAME_SOURCE),
            self::getTestAssetPublicId(self::RENAME_TARGET)
        );

        self::assertValidAsset($asset, ['public_id' => self::getTestAssetPublicId(self::RENAME_TARGET)]);

        $asset = self::$adminApi->asset(self::getTestAssetPublicId(self::RENAME_TARGET));

        self::assertValidAsset($asset, ['public_id' => self::getTestAssetPublicId(self::RENAME_TARGET)]);
    }

    /**
     * Try to rename an image to an existing image public id
     */
    public function testRenameToExistingImagePublicId()
    {
        $this->expectException(BadRequest::class);
        self::$uploadApi->rename(
            self::getTestAssetPublicId(self::RENAME_TO_EXISTING_SOURCE),
            self::getTestAssetPublicId(self::RENAME_TO_EXISTING_TARGET)
        );
    }

    /**
     * Rename an image to an existing image public id, overwriting existing images
     */
    public function testRenameAndOverwriteToExistingImagePublicId()
    {
        $resource = self::$uploadApi->rename(
            self::getTestAssetPublicId(self::RENAME_TO_EXISTING_SOURCE_OVERWRITE),
            self::getTestAssetPublicId(self::RENAME_TO_EXISTING_TARGET_OVERWRITE),
            ['overwrite' => true]
        );

        self::assertValidAsset(
            $resource,
            ['public_id' => self::getTestAssetPublicId(self::RENAME_TO_EXISTING_TARGET_OVERWRITE)]
        );

        $resource = self::$adminApi->asset(self::getTestAssetPublicId(self::RENAME_TO_EXISTING_TARGET_OVERWRITE));

        self::assertValidAsset(
            $resource,
            ['public_id' => self::getTestAssetPublicId(self::RENAME_TO_EXISTING_TARGET_OVERWRITE)]
        );
    }

    /**
     * Change an image type
     */
    public function testChangeImageType()
    {
        $mockUploadApi = new MockUploadApi();
        $mockUploadApi->rename(
            self::getTestAssetPublicId(self::CHANGE_TYPE),
            self::getTestAssetPublicId(self::CHANGE_TYPE),
            ['to_type' => 'private']
        );
        $lastRequest = $mockUploadApi->getMockHandler()->getLastRequest();

        self::assertRequestUrl($lastRequest, '/image/rename');
        self::assertRequestPost($lastRequest);
        self::assertRequestBodySubset(
            $lastRequest,
            [
                'to_type'        => 'private',
                'from_public_id' => self::getTestAssetPublicId(self::CHANGE_TYPE),
                'to_public_id'   => self::getTestAssetPublicId(self::CHANGE_TYPE),
            ]
        );
    }

    /**
     * Test context in the rename API response
     */
    public function testRenameReturnsContext()
    {
        $result = self::$uploadApi->rename(
            self::getTestAssetPublicId(self::RENAME_SOURCE_FOR_CONTEXT_TEST),
            self::getTestAssetPublicId(self::RENAME_TARGET_FOR_CONTEXT_TEST),
            ['context' => true]
        );

        self::assertArrayHasKey('context', $result);

        $result = self::$uploadApi->rename(
            self::getTestAssetPublicId(self::RENAME_TARGET_FOR_CONTEXT_TEST),
            self::getTestAssetPublicId(self::RENAME_SOURCE_FOR_CONTEXT_TEST)
        );

        self::assertArrayNotHasKey('context', $result);
    }

    /**
     * Test structured metadata in the rename API response
     */
    public function testMetadataInRename()
    {
        $result = self::$uploadApi->rename(
            self::getTestAssetPublicId(self::RENAME_SOURCE_FOR_METADATA_TEST),
            self::getTestAssetPublicId(self::RENAME_TARGET_FOR_METADATA_TEST),
            ['metadata' => true]
        );

        self::assertArrayHasKey('metadata', $result);

        $result = self::$uploadApi->rename(
            self::getTestAssetPublicId(self::RENAME_TARGET_FOR_METADATA_TEST),
            self::getTestAssetPublicId(self::RENAME_SOURCE_FOR_METADATA_TEST)
        );

        self::assertArrayNotHasKey('metadata', $result);
    }

    /**
     * Performs an eager transformation on a previously uploaded image
     */
    public function testEagerTransformation()
    {
        $asset = self::$uploadApi->explicit(
            self::getTestAssetPublicId(self::EXPLICIT),
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

    /**
     * Data provider of `testExpectUpdateException()` method.
     *
     * @return array[]
     */
    public function expectUpdateExceptionDataProvider()
    {
        return [
            'Illegal value for `raw_convert`' => [
                'publicId'         => self::EXCEPTION_RAW,
                'options'          => ['raw_convert' => 'illegal', 'resource_type' => 'raw'],
                'exception'        => BadRequest::class,
                'exceptionMessage' => 'Illegal value',
            ],

            'Illegal value for `categorization`' => [
                'publicId'         => self::EXCEPTION_IMAGE,
                'options'          => ['categorization' => 'illegal'],
                'exception'        => BadRequest::class,
                'exceptionMessage' => 'Illegal value',
            ],

            'Illegal value for `detection`' => [
                'publicId'         => self::EXCEPTION_IMAGE,
                'options'          => ['detection' => 'illegal'],
                'exception'        => BadRequest::class,
                'exceptionMessage' => "Illegal value",
            ],

            'Illegal value for `background_removal`' => [
                'publicId'         => self::EXCEPTION_IMAGE,
                'options'          => ['background_removal' => 'illegal'],
                'exception'        => BadRequest::class,
                'exceptionMessage' => 'Illegal value',
            ],
        ];
    }

    /**
     * Test wrong cases update a resource.
     *
     * @param string   $publicId
     * @param array    $options
     * @param ApiError $exception
     * @param string   $exceptionMessage
     *
     * @dataProvider expectUpdateExceptionDataProvider
     */
    public function testExpectUpdateException(
        $publicId,
        array $options,
        $exception,
        $exceptionMessage = null
    ) {
        $this->expectException($exception);
        $this->expectExceptionMessage($exceptionMessage);

        self::$adminApi->update(
            self::getTestAssetPublicId($publicId),
            $options
        );
    }
}
