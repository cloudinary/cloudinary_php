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
use Cloudinary\Asset\AssetType;
use Cloudinary\Asset\DeliveryType;
use Cloudinary\Asset\ModerationStatus;
use Cloudinary\Asset\ModerationType;
use Cloudinary\Test\Integration\IntegrationTestCase;

/**
 * Class AssetTest
 */
final class AssetTest extends IntegrationTestCase
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

        self::$UNIQUE_IMAGE_PUBLIC_ID = 'asset_image_' . self::$UNIQUE_TEST_ID;
        self::$UNIQUE_DOCX_PUBLIC_ID  = 'asset_docx_' . self::$UNIQUE_TEST_ID;
        self::$UNIQUE_CONTEXT_KEY     = 'asset_context_key_' . self::$UNIQUE_TEST_ID;
        self::$UNIQUE_CONTEXT_VALUE   = 'asset_context_value_' . self::$UNIQUE_TEST_ID;
        self::$UNIQUE_CONTEXT         = [self::$UNIQUE_CONTEXT_KEY => self::$UNIQUE_CONTEXT_VALUE];

        self::uploadTestAssetImage(
            [
                'public_id'         => self::$UNIQUE_IMAGE_PUBLIC_ID,
                ModerationType::KEY => ModerationType::MANUAL,
            ]
        );

        self::uploadTestAssetFile(
            [
                'public_id' => self::$UNIQUE_DOCX_PUBLIC_ID,
            ]
        );
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestAssets([AssetType::IMAGE, AssetType::RAW]);

        parent::tearDownAfterClass();
    }

    /**
     * Get uploaded image details without extra info
     */
    public function testGetUploadedImageDetailsNoExtraInfo()
    {
        $result = self::$adminApi->asset(self::$UNIQUE_IMAGE_PUBLIC_ID);

        self::assertValidAsset($result);
        self::assertArrayNotHasKey('accessibility_analysis', $result);
        self::assertArrayNotHasKey('colors', $result);
        self::assertArrayNotHasKey('exif', $result);
        self::assertArrayNotHasKey('faces', $result);
    }

    /**
     * Get uploaded image details including faces, colors and Exif info
     */
    public function testGetUploadedImageDetailsWithExtraInfo()
    {
        $result = self::$adminApi->asset(
            self::$UNIQUE_IMAGE_PUBLIC_ID,
            self::EXTRA_INFO
        );

        self::assertValidAsset(
            $result,
            [
                'colors' => [],
                'exif'   => [],
                'faces'  => [],
            ]
        );
    }

    /**
     * Get accessibility analysis of an an uploaded image
     */
    public function testGetUploadedImageAccessibilityAnalysis()
    {
        $result = self::$adminApi->asset(self::$UNIQUE_IMAGE_PUBLIC_ID, ['accessibility_analysis' => true]);

        self::assertArrayHasKey('accessibility_analysis', $result);
    }

    /**
     * Get Facebook picture details
     */
    public function testGetFacebookImageDetails()
    {
        $this->markTestSkipped('FIXME: Fetch facebook image before running this test');

        $result = self::$adminApi->asset('sample', [DeliveryType::KEY => DeliveryType::FACEBOOK]);

        self::assertValidAsset($result, [DeliveryType::KEY => DeliveryType::FACEBOOK]);
    }

    /**
     * Get uploaded raw file details
     */
    public function testGetUploadedRawFileDetails()
    {
        $result = self::$adminApi->asset(
            self::$UNIQUE_DOCX_PUBLIC_ID . '.docx',
            [
                AssetType::KEY => AssetType::RAW,
            ]
        );

        self::assertValidAsset(
            $result,
            [
                DeliveryType::KEY => DeliveryType::UPLOAD,
                AssetType::KEY    => AssetType::RAW,
                'bytes'           => 20453,
            ]
        );
    }

    /**
     * Update one or more of the attributes associated with a specified asset
     */
    public function testUpdateImageAttributes()
    {
        $result = self::$adminApi->update(
            self::$UNIQUE_IMAGE_PUBLIC_ID,
            [
                ModerationStatus::KEY => ModerationStatus::APPROVED,
                'context'             => self::$UNIQUE_CONTEXT,
            ]
        );

        self::assertValidAsset($result);
        self::assertEquals(ModerationStatus::APPROVED, $result['moderation'][0]['status']);
        self::assertEquals(self::$UNIQUE_CONTEXT, $result['context']['custom']);

        $result = self::$adminApi->asset(self::$UNIQUE_IMAGE_PUBLIC_ID);

        self::assertValidAsset($result);
        self::assertEquals(ModerationStatus::APPROVED, $result['moderation'][0]['status']);
        self::assertEquals(self::$UNIQUE_CONTEXT, $result['context']['custom']);
    }
}
