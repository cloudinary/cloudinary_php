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

use Cloudinary\Api\ApiClient;
use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Asset\DeliveryType;
use Cloudinary\Configuration\ApiConfig;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Test\Integration\IntegrationTestCase;
use Cloudinary\Transformation\Extract;
use Cloudinary\Transformation\Transformation;
use PHPUnit_Framework_Constraint_IsType as IsType;

/**
 * Class CreativeTest
 */
final class CreativeTest extends IntegrationTestCase
{
    const EXPLODE_GIF = 'explode_gif';

    const TRANSFORMATION   = ['width' => '0.5', 'crop' => 'crop'];
    const TRANSFORMATION_2 = ['width' => '100'];

    const URL_1 = 'https://res.cloudinary.com/demo/image/upload/sample';
    const URL_2 = 'https://res.cloudinary.com/demo/image/upload/car';

    const SPRITE_TEST_1 = 'sprite_test_1';
    const SPRITE_TEST_2 = 'sprite_test_2';
    const MULTI_TEST_1  = 'multi_test_1';
    const MULTI_TEST_2  = 'multi_test_2';

    private static $TAG_TO_MULTI;
    private static $TAG_TO_GENERATE_SPRITE;

    private static $TRANSFORMATION_STRING;

    /**
     * @throws ApiError
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$TAG_TO_MULTI           = 'upload_creative_multi_' . self::$UNIQUE_TEST_TAG;
        self::$TAG_TO_GENERATE_SPRITE = 'upload_creative_generate_sprite_' . self::$UNIQUE_TEST_TAG;

        self::$TRANSFORMATION_STRING = (string)(new Transformation(self::TRANSFORMATION));

        $tags = [
            self::$TAG_TO_GENERATE_SPRITE,
            self::$TAG_TO_MULTI,
        ];

        self::createTestAssets(
            [
                ['options' => ['tags' => $tags]],
                ['options' => ['tags' => $tags]],
                self::EXPLODE_GIF => ['options' => ['file' => self::TEST_IMAGE_GIF_PATH]],
                self::SPRITE_TEST_1 => ['option' => ['tags' => self::$TAG_TO_GENERATE_SPRITE]],
                self::SPRITE_TEST_2 => ['option' => ['tags' => self::$TAG_TO_GENERATE_SPRITE]],
                self::MULTI_TEST_1  => ['option' => ['tags' => self::$TAG_TO_MULTI]],
                self::MULTI_TEST_2  => ['option' => ['tags' => self::$TAG_TO_MULTI]],
            ]
        );
    }

    public static function tearDownAfterClass()
    {
        self::cleanupTestAssets();

        parent::tearDownAfterClass();
    }

    /**
     * Generating a sprite from given image urls or all images tagged with a certain tag.
     */
    public function testGenerateSprite()
    {
        $asset = self::$uploadApi->generateSprite(
            [
                'urls' => [
                    self::getTestAsset(self::SPRITE_TEST_1)['url'],
                    self::getTestAsset(self::SPRITE_TEST_2)['url'],
                ],
            ]
        );
        self::addAssetToCleanupList($asset, [DeliveryType::KEY => DeliveryType::SPRITE]);

        self::assertValidSprite($asset);
        self::assertCount(2, $asset['image_infos']);

        $asset = self::$uploadApi->generateSprite(self::$TAG_TO_GENERATE_SPRITE);
        self::addAssetToCleanupList($asset, [DeliveryType::KEY => DeliveryType::SPRITE]);

        self::assertValidSprite($asset);
        self::assertCount(2, $asset['image_infos']);

        $asset = self::$uploadApi->generateSprite(
            self::$TAG_TO_GENERATE_SPRITE,
            [
                'transformation' => self::TRANSFORMATION_2,
            ]
        );
        self::addAssetToCleanupList($asset, [DeliveryType::KEY => DeliveryType::SPRITE]);

        self::assertValidSprite($asset);
        self::assertContains('w_100', $asset['css_url']);

        $asset = self::$uploadApi->generateSprite(
            self::$TAG_TO_GENERATE_SPRITE,
            [
                'transformation' => new Transformation(['width' => 100]),
                'format'         => 'jpg',
            ]
        );
        self::addAssetToCleanupList($asset, [DeliveryType::KEY => DeliveryType::SPRITE]);

        self::assertValidSprite($asset);
        self::assertContains('w_100/f_jpg', $asset['css_url']);
    }

    /**
     * Generating an url to download a sprite from a provided array of URLs and from all images that have been assigned
     * a specified tag.
     */
    public function testDownloadGeneratedSprite()
    {
        $urlFromTag  = self::$uploadApi->downloadGeneratedSprite(self::$TAG_TO_GENERATE_SPRITE);
        $urlFromUrls = self::$uploadApi->downloadGeneratedSprite(
            [
                'urls' => [
                    self::URL_1,
                    self::URL_2,
                ],
            ]
        );

        self::assertDownloadSignUrl(
            $urlFromTag,
            ApiConfig::DEFAULT_UPLOAD_PREFIX,
            '/' . ApiClient::apiVersion() . '/' . Configuration::instance()->cloud->cloudName . '/image/sprite',
            [
                'mode' => UploadApi::MODE_DOWNLOAD,
                'tag'  => self::$TAG_TO_GENERATE_SPRITE,
            ]
        );
        self::assertDownloadSignUrl(
            $urlFromUrls,
            ApiConfig::DEFAULT_UPLOAD_PREFIX,
            '/' . ApiClient::apiVersion() . '/' . Configuration::instance()->cloud->cloudName . '/image/sprite',
            [
                'mode'    => UploadApi::MODE_DOWNLOAD,
                'api_key' => self::$uploadApi->getCloud()->apiKey,
                'urls'    => [
                    self::URL_1,
                    self::URL_2,
                ],
            ]
        );
    }

    /**
     * Creates a single animated image from given image urls or all image assets that have been assigned a certain tag.
     */
    public function testCreateMulti()
    {
        $asset = self::$uploadApi->multi(
            [
                'urls'           => [
                    self::getTestAsset(self::MULTI_TEST_1)['url'],
                    self::getTestAsset(self::MULTI_TEST_2)['url'],
                ],
                'transformation' => self::TRANSFORMATION,
            ]
        );
        self::addAssetToCleanupList($asset, [DeliveryType::KEY => DeliveryType::MULTI]);

        self::assertValidMulti($asset);
        self::assertStringEndsWith('.gif', $asset['url']);
        self::assertContains('w_0.5', $asset['url']);

        $asset = self::$uploadApi->multi(
            self::$TAG_TO_MULTI,
            [
                'transformation' => self::TRANSFORMATION,
            ]
        );
        self::addAssetToCleanupList($asset, [DeliveryType::KEY => DeliveryType::MULTI]);

        self::assertValidMulti($asset);
        self::assertStringEndsWith('.gif', $asset['url']);
        self::assertContains('w_0.5', $asset['url']);

        $asset = self::$uploadApi->multi(
            self::$TAG_TO_MULTI,
            [
                'transformation' => new Transformation(['width' => 111]),
                'format'         => 'pdf',
            ]
        );
        self::addAssetToCleanupList($asset, [DeliveryType::KEY => DeliveryType::MULTI]);

        self::assertValidMulti($asset);
        self::assertStringEndsWith('.pdf', $asset['url']);
        self::assertContains('w_111', $asset['url']);
    }

    /**
     * Generating an url to download an animated image from a provided array of URLs or from all images that have been
     * assigned a specified tag.
     */
    public function testDownloadMulti()
    {
        $urlFromTag  = self::$uploadApi->downloadMulti(self::$TAG_TO_MULTI);
        $urlFromUrls = self::$uploadApi->downloadMulti(
            [
                'urls' => [
                    self::URL_1,
                    self::URL_2,
                ],
            ]
        );

        self::assertDownloadSignUrl(
            $urlFromTag,
            ApiConfig::DEFAULT_UPLOAD_PREFIX,
            '/' . ApiClient::apiVersion() . '/' . Configuration::instance()->cloud->cloudName . '/image/multi',
            [
                'mode'    => UploadApi::MODE_DOWNLOAD,
                'api_key' => self::$uploadApi->getCloud()->apiKey,
                'tag'     => self::$TAG_TO_MULTI,
            ]
        );
        self::assertDownloadSignUrl(
            $urlFromUrls,
            ApiConfig::DEFAULT_UPLOAD_PREFIX,
            '/' . ApiClient::apiVersion() . '/' . Configuration::instance()->cloud->cloudName . '/image/multi',
            [
                'mode'    => UploadApi::MODE_DOWNLOAD,
                'api_key' => self::$uploadApi->getCloud()->apiKey,
                'urls'    => [
                    self::URL_1,
                    self::URL_2,
                ],
            ]
        );
    }

    /**
     * Explode a GIF
     */
    public function testExplodeGIF()
    {
        $result = self::$uploadApi->explode(
            self::getTestAssetPublicId(self::EXPLODE_GIF),
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
