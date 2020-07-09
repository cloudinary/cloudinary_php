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
use Cloudinary\Asset\ModerationStatus;
use Cloudinary\Asset\ModerationType;
use Cloudinary\Asset\AssetType;
use Cloudinary\Asset\DeliveryType;
use Cloudinary\Test\Integration\IntegrationTestCase;
use Cloudinary\Test\Unit\Asset\AssetTestCase;

/**
 * Class ResourceTest
 */
final class ResourceTest extends IntegrationTestCase
{
    const EXTRA_INFO = ['colors' => true, 'exif' => true, 'faces' => true];

    private static $UNIQUE_IMAGE_PUBLIC_ID;
    private static $UNIQUE_DOCX_PUBLIC_ID;
    private static $UNIQUE_CONTEXT;
    private static $UNIQUE_CONTEXT_KEY;
    private static $UNIQUE_CONTEXT_VALUE;

    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$UNIQUE_IMAGE_PUBLIC_ID = 'resource_image_' . self::$UNIQUE_TEST_ID;
        self::$UNIQUE_DOCX_PUBLIC_ID = 'resource_docx_' . self::$UNIQUE_TEST_ID;
        self::$UNIQUE_CONTEXT_KEY = 'resource_context_key_' . self::$UNIQUE_TEST_ID;
        self::$UNIQUE_CONTEXT_VALUE = 'resource_context_value_' . self::$UNIQUE_TEST_ID;
        self::$UNIQUE_CONTEXT = [self::$UNIQUE_CONTEXT_KEY => self::$UNIQUE_CONTEXT_VALUE];

        self::uploadTestResourceImage(
            [
                'public_id' => self::$UNIQUE_IMAGE_PUBLIC_ID,
                ModerationType::KEY => ModerationType::MANUAL
            ]
        );

        self::uploadTestResourceFile(
            [
                'public_id' => self::$UNIQUE_DOCX_PUBLIC_ID
            ]
        );
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestResources();
        self::cleanupTestResources(AssetType::RAW);

        parent::tearDownAfterClass();
    }

    /**
     * Get uploaded image details without extra info
     */
    public function testGetUploadedImageDetailsNoExtraInfo()
    {
        $result = self::$adminApi->resource(self::$UNIQUE_IMAGE_PUBLIC_ID);

        self::assertValidResource($result);
        $this->assertArrayNotHasKey('accessibility_analysis', $result);
        $this->assertArrayNotHasKey('colors', $result);
        $this->assertArrayNotHasKey('exif', $result);
        $this->assertArrayNotHasKey('faces', $result);
    }

    /**
     * Get uploaded image details including faces, colors and Exif info
     */
    public function testGetUploadedImageDetailsWithExtraInfo()
    {
        $result = self::$adminApi->resource(
            self::$UNIQUE_IMAGE_PUBLIC_ID,
            self::EXTRA_INFO
        );

        self::assertValidResource(
            $result,
            [
                'colors' => [],
                'exif' => [],
                'faces' => [],
            ]
        );
    }

    /**
     * Get accessibility analysis of an an uploaded image
     */
    public function testGetUploadedImageAccessibilityAnalysis()
    {
        $result = self::$adminApi->resource(self::$UNIQUE_IMAGE_PUBLIC_ID, ['accessibility_analysis' => true]);

        $this->assertArrayHasKey('accessibility_analysis', $result);
    }

    /**
     * Get Facebook picture details
     */
    public function testGetFacebookImageDetails()
    {
        $this->markTestSkipped('FIXME: Fetch facebook image before running this test');

        $result = self::$adminApi->resource('sample', [DeliveryType::KEY => DeliveryType::FACEBOOK]);

        self::assertValidResource($result, [DeliveryType::KEY => DeliveryType::FACEBOOK]);
    }

    /**
     * Get uploaded raw file details
     */
    public function testGetUploadedRawFileDetails()
    {
        $result = self::$adminApi->resource(
            self::$UNIQUE_DOCX_PUBLIC_ID . '.docx',
            [
                AssetType::KEY => AssetType::RAW
            ]
        );

        self::assertValidResource(
            $result,
            [
                DeliveryType::KEY => DeliveryType::UPLOAD,
                AssetType::KEY => AssetType::RAW,
                'bytes' => 20453
            ]
        );
    }

    /**
     * Update one or more of the attributes associated with a specified resource
     */
    public function testUpdateImageAttributes()
    {
        $result = self::$adminApi->update(
            self::$UNIQUE_IMAGE_PUBLIC_ID,
            [
                ModerationStatus::KEY => ModerationStatus::APPROVED,
                'context' => self::$UNIQUE_CONTEXT
            ]
        );

        self::assertValidResource($result);
        self::assertEquals(ModerationStatus::APPROVED, $result['moderation'][0]['status']);
        self::assertEquals(self::$UNIQUE_CONTEXT, $result['context']['custom']);

        $result = self::$adminApi->resource(self::$UNIQUE_IMAGE_PUBLIC_ID);

        self::assertValidResource($result);
        self::assertEquals(ModerationStatus::APPROVED, $result['moderation'][0]['status']);
        self::assertEquals(self::$UNIQUE_CONTEXT, $result['context']['custom']);
    }
}
