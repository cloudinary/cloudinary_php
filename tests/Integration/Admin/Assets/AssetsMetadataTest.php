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
use Cloudinary\Api\Metadata\StringMetadataField;
use Cloudinary\Asset\DeliveryType;
use Cloudinary\Asset\ModerationStatus;
use Cloudinary\Asset\ModerationType;
use Cloudinary\Test\Integration\IntegrationTestCase;

/**
 * Class AssetsMetadataTest
 */
final class AssetsMetadataTest extends IntegrationTestCase
{
    const CLASS_PREFIX = 'assets_metadata_test';

    private static $UNIQUE_CONTEXT;
    private static $UNIQUE_CONTEXT_KEY;
    private static $UNIQUE_CONTEXT_VALUE;
    private static $UNIQUE_TEST_TAG_TO_ONE_IMAGE_ASSET;
    private static $UNIQUE_TEST_METADATA;
    private static $UNIQUE_TEST_METADATA_EXTERNAL_ID;
    private static $UNIQUE_TEST_METADATA_DEFAULT_VALUE;
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
        self::$UNIQUE_TEST_METADATA_EXTERNAL_ID   = self::CLASS_PREFIX . 'metadata_external_id_' .
                                                    self::$UNIQUE_TEST_ID;
        self::$UNIQUE_TEST_METADATA_DEFAULT_VALUE = self::CLASS_PREFIX . 'metadata_default_value_' .
                                                    self::$UNIQUE_TEST_ID;

        self::$UNIQUE_TEST_METADATA = new StringMetadataField(self::$UNIQUE_TEST_METADATA_EXTERNAL_ID);
        self::$UNIQUE_TEST_METADATA->setExternalId(self::$UNIQUE_TEST_METADATA_EXTERNAL_ID);
        self::$UNIQUE_TEST_METADATA->setDefaultValue(self::$UNIQUE_TEST_METADATA_DEFAULT_VALUE);
        self::$UNIQUE_TEST_METADATA->setMandatory(true);
        self::$adminApi->addMetadataField(self::$UNIQUE_TEST_METADATA);

        self::createTestAssets(
            [
                self::$UNIQUE_PREFIX => [
                    'options' => [
                        'tags'              => [self::$UNIQUE_TEST_TAG_TO_ONE_IMAGE_ASSET],
                        'context'           => self::$UNIQUE_CONTEXT,
                        ModerationType::KEY => ModerationType::MANUAL,
                    ]
                ],
            ],
            self::CLASS_PREFIX
        );
    }

    /**
     * @throws ApiError
     */
    public static function tearDownAfterClass()
    {
        self::cleanupTestAssets();
        self::$adminApi->deleteMetadataField(self::$UNIQUE_TEST_METADATA_EXTERNAL_ID);

        parent::tearDownAfterClass();
    }

    /**
     * Request structured metadata to be returned in the response of the assets API response.
     */
    public function testStructuredMetadataInAssets()
    {
        $result = self::$adminApi->assets(
            [
                "prefix"   => self::$FULL_UNIQUE_PREFIX,
                "type"     => DeliveryType::UPLOAD,
                "metadata" => true,
            ]
        );

        foreach ($result['resources'] as $asset) {
            self::assertArrayHasKey('metadata', $asset);
        }

        $result = self::$adminApi->assets(
            [
                "prefix"   => self::$FULL_UNIQUE_PREFIX,
                "type"     => DeliveryType::UPLOAD,
                "metadata" => false,
            ]
        );

        foreach ($result['resources'] as $asset) {
            self::assertArrayNotHasKey('metadata', $asset);
        }
    }

    /**
     * Request structured metadata to be returned in the response of the assets by tag API.
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
     * Request structured metadata to be returned in the response of the assets by context API.
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
     * Request structured metadata to be returned in the response of the assets by moderation API.
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
