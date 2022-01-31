<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Cloudinary;

use Cloudinary\Asset\Image;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Tag\ClientHintsMetaTag;
use Cloudinary\Tag\FormTag;
use Cloudinary\Tag\ImageTag;
use Cloudinary\Tag\JsConfigTag;
use Cloudinary\Tag\PictureTag;
use Cloudinary\Tag\SpriteTag;
use Cloudinary\Tag\UploadTag;
use Cloudinary\Test\Unit\Asset\AssetTestCase;
use Cloudinary\Tag\VideoTag;

/**
 * Class CloudinaryTagTest
 */
class CloudinaryTagTest extends AssetTestCase
{
    /**
     * @var Cloudinary $c Client instance
     */
    private $c;

    /**
     * @var Image $src
     */
    private $src;

    public function setUp()
    {
        parent::setUp();

        $this->c = new Cloudinary(Configuration::instance());

        $this->src = new Image(self::IMAGE_NAME);
    }

    public function testSimpleCloudinaryImageTag()
    {
        self::assertEquals(
            '<img src="'.$this->src.'">',
            $this->c->imageTag(self::IMAGE_NAME)
        );
    }

    public function testCloudinaryImageTagWithCustomCloudinaryConfig()
    {
        $this->c->configuration->url->cname(self::TEST_HOSTNAME)->secure(false);

        $expectedImage = $this->c->image(self::IMAGE_NAME);

        self::assertContains(self::TEST_HOSTNAME, (string)$expectedImage);

        self::assertStrEquals(
            '<img src="'.$expectedImage.'">',
            $this->c->imageTag(self::IMAGE_NAME)
        );

        self::assertStrEquals(
            '<img src="'.$expectedImage.'">',
            $this->c->imageTag($expectedImage)
        );

        self::assertStrEquals(
            '<img src="'.$expectedImage.'">',
            $this->c->tag()->imageTag($expectedImage)
        );

        self::assertStrEquals(
            '<img src=\''.$expectedImage.'\'/>',
            $this->c->imageTag(ImageTag::fromParams(self::IMAGE_NAME))
        );
    }

    public function testCloudinaryVideoTag()
    {
        $expected = new VideoTag(self::VIDEO_NAME);

        self::assertStrEquals(
            $expected,
            $this->c->videoTag(self::VIDEO_NAME)
        );

        self::assertStrEquals(
            $expected,
            $this->c->tag()->videoTag(self::VIDEO_NAME)
        );
    }

    public function testCloudinaryTags()
    {
        self::assertStrEquals(
            new ClientHintsMetaTag(),
            $this->c->tag()->clientHintsMetaTag()
        );

        self::assertStrEquals(
            new UploadTag('field_name'),
            $this->c->tag()->uploadTag('field_name')
        );

        self::assertStrEquals(
            new FormTag(),
            $this->c->tag()->formTag()
        );

        self::assertStrEquals(
            new JsConfigTag(),
            $this->c->tag()->jsConfigTag()
        );

        self::assertStrEquals(
            new SpriteTag(self::$TEST_TAG),
            $this->c->tag()->spriteTag(self::$TEST_TAG)
        );

        self::assertStrEquals(
            new PictureTag(self::ASSET_ID, []),
            $this->c->tag()->pictureTag(self::ASSET_ID, [])
        );
    }
}
