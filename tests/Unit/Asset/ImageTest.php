<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Asset;

use Cloudinary\Asset\DeliveryType;
use Cloudinary\Asset\Image;
use Cloudinary\Transformation\Adjust;
use Cloudinary\Transformation\Argument\Color;
use Cloudinary\Transformation\AspectRatio;
use Cloudinary\Transformation\Background;
use Cloudinary\Transformation\Conditional;
use Cloudinary\Transformation\Expression\UVar;
use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\ImageSource;
use Cloudinary\Transformation\Pad;
use Cloudinary\Transformation\Scale;
use Cloudinary\Transformation\Transformation;
use Monolog\Logger as Monolog;
use ReflectionException;
use UnexpectedValueException;

/**
 * Class SampleTest
 */
final class ImageTest extends AssetTestCase
{
    const SEO_NAME    = 'my_favorite_sample';
    const FACEBOOK_ID = 65646572251;
    const YOUTUBE_ID  = 'o-urnlaJpOA';
    const VIMEO_ID    = 103437078;
    const SPRITE_TAG  = 'sample_tag';

    /**
     * @var Image $image Test image that is commonly reused by tests
     */
    protected $image;

    public function setUp()
    {
        parent::setUp();

        $this->image = new Image(self::IMAGE_NAME);
    }

    public function testSimpleImage()
    {
        self::assertImageUrl(
            self::IMAGE_NAME,
            $this->image
        );

        self::assertImageUrl(
            self::IMAGE_NAME,
            $this->image->toUrl()
        );
    }

    /**
     * @throws ReflectionException
     */
    public function testSuffixForNotSupportedDeliveryType()
    {
        $this->image->asset->suffix       = self::SEO_NAME;
        $this->image->asset->deliveryType = DeliveryType::PUBLIC_DELIVERY;

        $message                  = null;
        $expectedExceptionMessage = 'URL Suffix is only supported in ';
        try {
            $this->image->toUrl();
        } catch (UnexpectedValueException $e) {
            $message = $e->getMessage();
        }

        self::assertStringStartsWith($expectedExceptionMessage, $message);
        self::assertObjectLoggedMessage($this->image, $expectedExceptionMessage, Monolog::CRITICAL);
    }

    public function testFetchImage()
    {
        $image = Image::fetch(self::FETCH_IMAGE_URL);

        self::assertImageUrl(self::FETCH_IMAGE_URL, $image, ['delivery_type' => DeliveryType::FETCH]);
    }

    public function testFacebookImage()
    {
        $image = Image::facebook(self::FACEBOOK_ID);

        self::assertImageUrl(self::FACEBOOK_ID, $image, ['delivery_type' => DeliveryType::FACEBOOK]);
    }

    public function testVideoThumbnailImages()
    {
        $image = Image::youTube(self::YOUTUBE_ID);

        self::assertImageUrl(self::YOUTUBE_ID, $image, ['delivery_type' => DeliveryType::YOUTUBE]);


        $image = Image::vimeo(self::VIMEO_ID);

        self::assertImageUrl(self::VIMEO_ID, $image, ['delivery_type' => DeliveryType::VIMEO]);
    }

    public function testSpriteImage()
    {
        $image = Image::sprite(self::SPRITE_TAG);

        self::assertImageUrl(self::SPRITE_TAG . '.css', $image, ['delivery_type' => DeliveryType::SPRITE]);
    }

    public function testImageSEO()
    {
        $image = new Image(self::IMAGE_NAME);

        self::assertAssetUrl(
            'images/' . self::ASSET_ID . '/' . self::SEO_NAME . '.' . self::IMG_EXT,
            $image->suffix(self::SEO_NAME)
        );

        self::assertAssetUrl(
            'iu/' . self::ASSET_ID . '/' . self::SEO_NAME . '.' . self::IMG_EXT,
            $image->shorten()
        );

        self::assertAssetUrl(
            self::ASSET_ID . '/' . self::SEO_NAME . '.' . self::IMG_EXT,
            $image->useRootPath()
        );

        $iNoFormat = new Image(pathinfo(self::IMAGE_NAME, PATHINFO_FILENAME));

        self::assertAssetUrl(
            'images/' . self::ASSET_ID . '/' . self::SEO_NAME,
            (string)$iNoFormat->suffix(self::SEO_NAME)
        );
    }

    public function testImage()
    {
        $this->image
            ->resize(
                Pad::limitPad(
                    170,
                    180
                )->background(
                    Background::predominantGradient(2)->palette('cyan', 'magenta', 'yellow', 'black')
                )->gravity(Gravity::southWest())
            )->effect(Adjust::replaceColor(Color::GREEN, 17, Color::RED));

        $this->image->overlay(ImageSource::image($this->image->getPublicId()));

        $bColorStr = 'b_auto:predominant_gradient:2:palette_cyan_magenta_yellow_black';

        $t_str = "{$bColorStr},c_lpad,g_south_west,h_180,w_170/e_replace_color:green:17:red";

        self::assertImageUrl(
            "{$t_str}/l_" . self::IMAGE_NAME . '/fl_layer_apply/' . self::IMAGE_NAME,
            $this->image
        );

        $this->image->conditional(
            Conditional::ifCondition(
                UVar::uVar('var')->multiply()->int(2)->lessThanOrEqual()->float(997.997),
                Scale::limitFit(101, 201)
            )->otherwise(
                (new Transformation())
                    ->resize(Scale::fit(102, 202))
                    ->resize(Scale::scale((202))->aspectRatio(AspectRatio::ignoreInitialAspectRatio()))
            )
        );

        $elseTransStr = 'c_fit,h_202,w_102/c_scale,fl_ignore_aspect_ratio,w_202';

        $expectedExpression = "if_\$var_mul_2_lte_997.997/c_limit,h_201,w_101/if_else/{$elseTransStr}/if_end";

        self::assertImageUrl(
            "{$t_str}/l_" . self::IMAGE_NAME . "/fl_layer_apply/{$expectedExpression}/" .
            self::IMAGE_NAME,
            $this->image
        );
    }

    public function testImageTransformationFromParams()
    {
        self::assertImageUrl(
            'e_cartoonify/r_max/co_lightblue,e_outline:100/b_lightblue/c_scale,h_300/eo_3,so_1/' . self::IMAGE_NAME,
            $this->image->addActionFromQualifiers(
                [
                    'transformation' => [
                        ['effect' => 'cartoonify'],
                        ['radius' => 'max'],
                        ['effect' => 'outline:100', 'color' => 'lightblue'],
                        ['background' => 'lightblue'],
                        ['height' => 300, 'crop' => 'scale'],
                        ['offset' => '1..3'],
                    ],
                ]
            )
        );
    }

    public function testImageResize()
    {
        self::assertImageUrl(
            'c_scale,h_200,w_100/c_fill,g_auto,h_160,w_80/' . self::IMAGE_NAME,
            $this->image->scale(100, 200)->fill(80, 160, Gravity::auto())
        );
    }

    public function testImageCustomConfiguration()
    {
        self::assertImageUrl(
            self::IMAGE_NAME,
            new Image(self::IMAGE_NAME, ['cloud' => ['cloud_name' => 'custom_cloud'], 'url' => ['analytics'=> false]]),
            ['cloud_name' => 'custom_cloud']
        );

        self::assertImageUrl(
            self::IMAGE_NAME,
            (new Image(self::IMAGE_NAME))->cloudName('custom_cloud'),
            ['cloud_name' => 'custom_cloud']
        );
    }

    public function testImageToJson()
    {
        self::assertEquals(
            '{"asset":{"asset_type":"image","delivery_type":"upload","filename":"sample","extension":"png"},' .
            '"cloud":{"cloud_name":"test123"},"url":{"analytics":false}}',
            json_encode($this->image)
        );
    }
}
