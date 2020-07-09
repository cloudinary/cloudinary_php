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

        $this->c = new Cloudinary($this->cloudinaryUrl);

        $this->src = new Image(self::IMAGE_NAME);
    }

    public function testSimpleCloudinaryImageTag()
    {
        $this->assertEquals(
            '<img src="'.$this->src.'">',
            $this->c->imageTag(self::IMAGE_NAME)
        );
    }

    public function testCloudinaryImageTagWithCustomCloudinaryConfig()
    {

        $this->c->configuration->url->cname = self::TEST_HOSTNAME;

        $expectedImage = $this->c->image(self::IMAGE_NAME);

        $this->assertEquals(
            '<img src="'.$expectedImage.'">',
            $this->c->imageTag(self::IMAGE_NAME)
        );

        $this->assertEquals(
            '<img src="'.$expectedImage.'">',
            $this->c->imageTag($expectedImage)
        );
    }

    public function testCloudinaryVideoTag()
    {
        $tag = $this->c->videoTag(self::VIDEO_NAME);

        $expected = new VideoTag(self::VIDEO_NAME);

        $this->assertStrEquals(
            $expected,
            $tag
        );
    }
}
