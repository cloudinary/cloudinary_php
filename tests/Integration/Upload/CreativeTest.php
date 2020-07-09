<?php

namespace Cloudinary\Test\Integration\Upload;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Asset\DeliveryType;
use Cloudinary\Test\Integration\IntegrationTestCase;
use Cloudinary\Transformation\Page;
use Cloudinary\Transformation\Transformation;
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

        self::uploadTestResourceImage(['tags' => $tags, 'public_id' => self::$UNIQUE_TEST_ID]);
        self::uploadTestResourceImage(['tags' => $tags]);
        self::uploadTestResourceImage(['public_id' => self::$EXPLODE_GIF_PUBLIC_ID], self::TEST_IMAGE_GIF_PATH);
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestResources();
        self::cleanupResources();

        parent::tearDownAfterClass();
    }

    /**
     * Generating a sprite from all images tagged with a certain tag
     */
    public function testGenerateSprite()
    {
        $resource = self::$uploadApi->generateSprite(self::$TAG_TO_GENERATE_SPRITE);
        self::addResourceToCleanupList($resource['public_id'], [DeliveryType::KEY => DeliveryType::SPRITE]);

        self::assertResourceUrl($resource, 'css_url', 'css', DeliveryType::SPRITE);
        self::assertResourceUrl($resource, 'image_url', 'png', DeliveryType::SPRITE);
        self::assertResourceUrl($resource, 'json_url', 'json', DeliveryType::SPRITE);
        self::assertResourceUrl($resource, 'secure_css_url', 'css', DeliveryType::SPRITE);
        self::assertResourceUrl($resource, 'secure_image_url', 'png', DeliveryType::SPRITE);
        self::assertResourceUrl($resource, 'secure_json_url', 'json', DeliveryType::SPRITE);
        $this->assertEquals(self::$TAG_TO_GENERATE_SPRITE, $resource['public_id']);

        $this->assertCount(2, $resource['image_infos']);

        foreach ($resource['image_infos'] as $imageInfo) {
            self::assertObjectStructure(
                $imageInfo,
                [
                    'width'  => IsType::TYPE_INT,
                    'height' => IsType::TYPE_INT,
                    'x'      => IsType::TYPE_INT,
                    'y'      => IsType::TYPE_INT,
                ]
            );
            $this->assertNotEmpty($imageInfo['width']);
            $this->assertNotEmpty($imageInfo['height']);
        }
    }

    /**
     * Creates a single animated image from all image assets that have been assigned a certain tag
     */
    public function testCreateMulti()
    {
        $resource = self::$uploadApi->multi(self::$TAG_TO_MULTI);
        self::addResourceToCleanupList($resource['public_id'], [DeliveryType::KEY => DeliveryType::MULTI]);

        self::assertResourceUrl($resource, 'url', 'gif', DeliveryType::MULTI);
        self::assertResourceUrl($resource, 'secure_url', 'gif', DeliveryType::MULTI);
        $this->assertEquals(self::$TAG_TO_MULTI, $resource['public_id']);
    }

    /**
     * Explode a GIF
     */
    public function testExplodeGIF()
    {
        $result = self::$uploadApi->explode(
            self::$EXPLODE_GIF_PUBLIC_ID,
            [
                'transformation' => Page::all()
            ]
        );

        $this->assertEquals('processing', $result['status']);
        $this->assertNotEmpty($result['batch_id']);
        self::assertObjectStructure($result, ['batch_id' => IsType::TYPE_STRING]);
    }

    /**
     * Create an image of the text string
     */
    public function testCreateImageOfTextString()
    {
        $resource = self::$uploadApi->text(self::$UNIQUE_TEST_ID);
        self::addResourceToCleanupList($resource['public_id'], [DeliveryType::KEY => DeliveryType::TEXT]);

        self::assertValidResource(
            $resource,
            [
                DeliveryType::KEY => DeliveryType::TEXT,
                'format'          => 'png',
            ]
        );
    }
}
