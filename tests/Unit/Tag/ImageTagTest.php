<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Tag;

use Cloudinary\Asset\Image;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Tag\ImageTag;
use Cloudinary\Test\Unit\Asset\ImageTest;
use Cloudinary\Transformation\Scale;
use InvalidArgumentException;
use Monolog\Logger as Monolog;

/**
 * Class ImageTagTest
 */
final class ImageTagTest extends ImageTagTestCase
{
    protected $breakpointsConf;

    public function setUp()
    {
        parent::setUp();

        $this->breakpointsConf = new Configuration(Configuration::instance());

        $this->breakpointsConf->responsiveBreakpoints->minWidth  = self::MIN_WIDTH;
        $this->breakpointsConf->responsiveBreakpoints->maxWidth  = self::MAX_WIDTH;
        $this->breakpointsConf->responsiveBreakpoints->maxImages = self::MAX_IMAGES;

        $this->breakpointsConf->responsiveBreakpoints->autoOptimalBreakpoints = true;
    }

    public function testImageTagFromPublicId()
    {
        self::assertImageTag(
            [$this->src],
            new ImageTag(self::IMAGE_NAME)
        );
    }

    public function testImageTagFromParams()
    {
        self::assertStrEquals(
            "<img src='https://hello.com/test123/image/upload/c_scale,w_500/sample.png' dummy width='500'/>",
            ImageTag::fromParams(
                self::IMAGE_NAME,
                [
                    'crop'                => 'scale',
                    'width'               => 500,
                    'secure'              => true,
                    'secure_distribution' => self::TEST_HOSTNAME,
                    'dummy',
                ]
            )
        );
    }

    public function testImageTagFromImage()
    {
        self::assertImageTag(
            [$this->src],
            new ImageTag($this->src)
        );
    }

    public function testImageTagFetchImage()
    {
        self::assertImageTag(
            [Image::fetch(self::FETCH_IMAGE_URL)],
            ImageTag::fetch(self::FETCH_IMAGE_URL)
        );
    }

    public function testImageTagFromImageWithTransformation()
    {
        $transformedImage = (new Image(self::IMAGE_NAME))->rotate(17);
        self::assertImageTag(
            [$transformedImage],
            new ImageTag($transformedImage)
        );
    }

    public function testImageTagCustomConfiguration()
    {
        $configuration               = Configuration::instance();
        $configuration->url->shorten = true;

        // Can reference this image, since its functionality it tested in another test
        $expectedImage = new Image(self::IMAGE_NAME, $configuration);

        // Here we test that configuration is not lost while passing it further
        self::assertImageTag(
            [$expectedImage],
            new ImageTag(self::IMAGE_NAME, $configuration)
        );

        // Test configuration builders
        self::assertImageTag(
            [$expectedImage->cloudName(self::CUSTOM_CLOUD_NAME)],
            (new ImageTag(self::IMAGE_NAME))->cloudName(self::CUSTOM_CLOUD_NAME)->shorten()
        );
    }

    public function testImageTagSrcSetAutoOptimalBreakpointsFromConfig()
    {
        $conf = new Configuration($this->breakpointsConf);

        $conf->responsiveBreakpoints->autoOptimalBreakpoints = true;

        self::assertImageTag(
            [$this->src, self::BREAKPOINTS_ARR],
            new ImageTag(self::IMAGE_NAME, $conf)
        );
    }

    public function testImageTagBreakpointsRelativeWidth()
    {
        $conf                                                = new Configuration($this->breakpointsConf);
        $conf->responsiveBreakpoints->autoOptimalBreakpoints = true;
        $conf->tag->relativeWidth                            = 0.5;

        self::assertImageTag(
            [$this->src, self::BREAKPOINTS_ARR_HALF],
            new ImageTag(self::IMAGE_NAME, $conf)
        );
    }

    public function testImageTagTransformations()
    {
        self::assertImageTag(
            [$this->src->resize(Scale::scale(self::MAX_WIDTH))],
            ImageTag::upload(self::IMAGE_NAME)->resize(Scale::scale(self::MAX_WIDTH))
        );
    }

    public function testImageTagScale()
    {
        self::assertImageTag(
            [$this->src->resize(Scale::scale(self::MAX_WIDTH))],
            ImageTag::upload(self::IMAGE_NAME)->scale(self::MAX_WIDTH)
        );
    }

    public function testImageTagStatelessSerialize()
    {
        $tag         = new ImageTag(self::IMAGE_NAME, $this->breakpointsConf);
        $originalTag = new ImageTag(self::IMAGE_NAME, $this->breakpointsConf);

        $tag->serialize();

        self::assertObjectsEqual($tag, $originalTag);
    }

    public function testFacebookImageTag()
    {
        self::assertImageTag(
            [Image::facebook(self::TEST_SOCIAL_PROFILE_ID)],
            ImageTag::facebook(self::TEST_SOCIAL_PROFILE_ID)
        );
    }

    public function testGravatarImageTag()
    {
        self::assertImageTag(
            [Image::gravatar(self::TEST_EMAIL)],
            ImageTag::gravatar(self::TEST_EMAIL)
        );
    }

    public function testTwitterImageTag()
    {
        self::assertImageTag(
            [Image::twitter(self::TEST_SOCIAL_PROFILE_ID)],
            ImageTag::twitter(self::TEST_SOCIAL_PROFILE_ID)
        );
    }

    public function testTwitterNameImageTag()
    {
        self::assertImageTag(
            [Image::twitterName(self::TEST_SOCIAL_PROFILE_NAME)],
            ImageTag::twitterName(self::TEST_SOCIAL_PROFILE_NAME)
        );
    }

    public function testYouTubeThumbnailImageTag()
    {
        self::assertImageTag(
            [Image::youTube(ImageTest::YOUTUBE_ID)],
            ImageTag::youTube(ImageTest::YOUTUBE_ID)
        );
    }

    public function testImageTagStaticBreakpointsLoggingWrongWidth()
    {
        $message                                      = null;
        $tag                                          = new ImageTag(self::IMAGE_NAME, $this->breakpointsConf);
        $tag->config->responsiveBreakpoints->minWidth = self::MAX_WIDTH;
        $tag->config->responsiveBreakpoints->maxWidth = self::MIN_WIDTH;
        try {
            $tag->srcset->getBreakpoints();
        } catch (InvalidArgumentException $e) {
            $message = $e->getMessage();
        }
        self::assertObjectLoggedMessage($tag->srcset, $message, Monolog::CRITICAL);
    }

    public function testImageTagStaticBreakpointsExceptionWrongWidth()
    {
        $this->expectException(InvalidArgumentException::class);

        $tag                                          = new ImageTag(self::IMAGE_NAME, $this->breakpointsConf);
        $tag->config->responsiveBreakpoints->minWidth = self::MAX_WIDTH;
        $tag->config->responsiveBreakpoints->maxWidth = self::MIN_WIDTH;
        $tag->srcset->getBreakpoints();
    }

    public function testImageTagStaticBreakpointsLoggingWrongMaxImage()
    {
        $tag                                           = new ImageTag(self::IMAGE_NAME, $this->breakpointsConf);
        $tag->config->responsiveBreakpoints->maxImages = -1;

        $message                  = null;
        $expectedExceptionMessage = 'maxImages must be a positive integer';
        try {
            $tag->srcset->getBreakpoints();
        } catch (InvalidArgumentException $e) {
            $message = $e->getMessage();
        }
        self::assertStringStartsWith($expectedExceptionMessage, $message);
        self::assertObjectLoggedMessage($tag->srcset, $expectedExceptionMessage, Monolog::CRITICAL);
    }

    public function testImageTagStaticBreakpointsExceptionWrongMaxImage()
    {
        $tag                                           = new ImageTag(self::IMAGE_NAME, $this->breakpointsConf);
        $tag->config->responsiveBreakpoints->maxImages = -1;

        $this->expectException(InvalidArgumentException::class);
        $tag->srcset->getBreakpoints();
    }

    public function testImageTagStaticBreakpointsLoggingWrongAttributes()
    {
        $tag                                           = new ImageTag(self::IMAGE_NAME, $this->breakpointsConf);
        $tag->config->responsiveBreakpoints->minWidth  = 0;
        $tag->config->responsiveBreakpoints->maxWidth  = 0;
        $tag->config->responsiveBreakpoints->maxImages = 0;

        $message                  = null;
        $expectedExceptionMessage = 'Either valid (minWidth, maxWidth, maxImages) or breakpointsmust be provided to ' .
                                    'the image srcset attribute';
        try {
            $tag->srcset->getBreakpoints();
        } catch (InvalidArgumentException $e) {
            $message = $e->getMessage();
        }
        self::assertStringStartsWith($expectedExceptionMessage, $message);
        self::assertObjectLoggedMessage($tag->srcset, $expectedExceptionMessage, Monolog::CRITICAL);
    }

    public function testImageTagStaticBreakpointsExceptionAttributes()
    {
        $tag                                           = new ImageTag(self::IMAGE_NAME, $this->breakpointsConf);
        $tag->config->responsiveBreakpoints->minWidth  = 0;
        $tag->config->responsiveBreakpoints->maxWidth  = 0;
        $tag->config->responsiveBreakpoints->maxImages = 0;

        $this->expectException(InvalidArgumentException::class);

        $tag->srcset->getBreakpoints();
    }
}
