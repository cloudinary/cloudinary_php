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
use Cloudinary\Asset\DeliveryType;
use Cloudinary\Test\Integration\IntegrationTestCase;
use Cloudinary\Transformation\Extract;
use PHPUnit_Framework_Constraint_IsType as IsType;

/**
 * Class CreativeTest
 */
final class CreativeTest extends IntegrationTestCase
{
    private static $TAG_TO_MULTI;
    private static $TAG_TO_GENERATE_SPRITE;
    private static $EXPLODE_GIF_PUBLIC_ID;

    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$TAG_TO_MULTI           = 'upload_creative_multi_' . self::$UNIQUE_TEST_TAG;
        self::$TAG_TO_GENERATE_SPRITE = 'upload_creative_generate_sprite_' . self::$UNIQUE_TEST_TAG;
        self::$EXPLODE_GIF_PUBLIC_ID  = 'upload_creative_explode_gif_' . self::$UNIQUE_TEST_ID;

        $tags = [
            self::$TAG_TO_GENERATE_SPRITE,
            self::$TAG_TO_MULTI,
        ];

        self::uploadTestAssetImage(['tags' => $tags, 'public_id' => self::$UNIQUE_TEST_ID]);
        self::uploadTestAssetImage(['tags' => $tags]);
        self::uploadTestAssetImage(['public_id' => self::$EXPLODE_GIF_PUBLIC_ID], self::TEST_IMAGE_GIF_PATH);
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestAssets();

        parent::tearDownAfterClass();
    }

    /**
     * Generating a sprite from all images tagged with a certain tag
     */
    public function testGenerateSprite()
    {
        $asset = self::$uploadApi->generateSprite(self::$TAG_TO_GENERATE_SPRITE);
        self::addAssetToCleanupList($asset, [DeliveryType::KEY => DeliveryType::SPRITE]);

        self::assertAssetUrl($asset, 'css_url', 'css', DeliveryType::SPRITE);
        self::assertAssetUrl($asset, 'image_url', 'png', DeliveryType::SPRITE);
        self::assertAssetUrl($asset, 'json_url', 'json', DeliveryType::SPRITE);
        self::assertAssetUrl($asset, 'secure_css_url', 'css', DeliveryType::SPRITE);
        self::assertAssetUrl($asset, 'secure_image_url', 'png', DeliveryType::SPRITE);
        self::assertAssetUrl($asset, 'secure_json_url', 'json', DeliveryType::SPRITE);
        self::assertEquals(self::$TAG_TO_GENERATE_SPRITE, $asset['public_id']);

        self::assertCount(2, $asset['image_infos']);

        foreach ($asset['image_infos'] as $imageInfo) {
            self::assertObjectStructure(
                $imageInfo,
                [
                    'width'  => IsType::TYPE_INT,
                    'height' => IsType::TYPE_INT,
                    'x'      => IsType::TYPE_INT,
                    'y'      => IsType::TYPE_INT,
                ]
            );
            self::assertNotEmpty($imageInfo['width']);
            self::assertNotEmpty($imageInfo['height']);
        }
    }

    /**
     * Creates a single animated image from all image assets that have been assigned a certain tag
     */
    public function testCreateMulti()
    {
        $asset = self::$uploadApi->multi(self::$TAG_TO_MULTI);
        self::addAssetToCleanupList($asset, [DeliveryType::KEY => DeliveryType::MULTI]);

        self::assertAssetUrl($asset, 'url', 'gif', DeliveryType::MULTI);
        self::assertAssetUrl($asset, 'secure_url', 'gif', DeliveryType::MULTI);
        self::assertEquals(self::$TAG_TO_MULTI, $asset['public_id']);
    }

    /**
     * Explode a GIF
     */
    public function testExplodeGIF()
    {
        $result = self::$uploadApi->explode(
            self::$EXPLODE_GIF_PUBLIC_ID,
            [
                'transformation' => Extract::getPage()->all(),
            ]
        );

        self::assertEquals('processing', $result['status']);
        self::assertNotEmpty($result['batch_id']);
        self::assertObjectStructure($result, ['batch_id' => IsType::TYPE_STRING]);
    }

    /**
     * Create an image of the text string
     */
    public function testCreateImageOfTextString()
    {
        $asset = self::$uploadApi->text(self::$UNIQUE_TEST_ID);
        self::addAssetToCleanupList($asset, [DeliveryType::KEY => DeliveryType::TEXT]);

        self::assertValidAsset(
            $asset,
            [
                DeliveryType::KEY => DeliveryType::TEXT,
                'format'          => 'png',
            ]
        );
        self::assertGreaterThan(5, $asset['width']);
        self::assertGreaterThan(5, $asset['height']);
    }
}
