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
use Cloudinary\Tag\PictureSourceTag;

/**
 * Class PictureSourceTagTest
 */
final class PictureSourceTagTest extends ImageTagTestCase
{
    public function testPictureSourceTagFromPublicId()
    {
        self::assertPictureSourceTag(
            [$this->src],
            new PictureSourceTag(self::IMAGE_NAME)
        );
    }

    public function testPictureSourceTagFromImage()
    {
        self::assertPictureSourceTag(
            [$this->src],
            new PictureSourceTag($this->src)
        );
    }

    public function testPictureSourceTagFetchImage()
    {
        self::assertPictureSourceTag(
            [Image::fetch(self::FETCH_IMAGE_URL)],
            PictureSourceTag::fetch(self::FETCH_IMAGE_URL)
        );
    }

    public function testPictureSourceTagFromImageWithTransformation()
    {
        $transformedImage = (new Image(self::IMAGE_NAME))->rotate(17);
        self::assertPictureSourceTag(
            [$transformedImage],
            new PictureSourceTag($transformedImage)
        );
    }

    public function testPictureSourceTagCustomConfiguration()
    {
        $configuration               = new Configuration();
        $configuration->url->shorten = true;

        // Can reference this image, since its functionality it tested in another test
        $expectedImage = new Image(self::IMAGE_NAME, $configuration);

        // Here we test that configuration is not lost while passing it further
        self::assertPictureSourceTag(
            [$expectedImage],
            new PictureSourceTag(self::IMAGE_NAME, $configuration)
        );
    }

    public function testPictureSourceTagSrcSet()
    {
        $configuration = new Configuration();

        $configuration->responsiveBreakpoints->minWidth  = self::MIN_WIDTH;
        $configuration->responsiveBreakpoints->maxWidth  = self::MAX_WIDTH;
        $configuration->responsiveBreakpoints->maxImages = self::MAX_IMAGES;

        $expectedImage = new Image(self::IMAGE_NAME, $configuration);

        self::assertPictureSourceTag(
            [$expectedImage, self::BREAKPOINTS_ARR],
            new PictureSourceTag(self::IMAGE_NAME, $configuration)
        );
    }

    public function testPictureSourceTagMediaAttribute()
    {
        self::assertPictureSourceTag(
            [
                $this->src,
                null,
                ['media' => '(min-width: '.self::MIN_WIDTH.'px) and (max-width: '.self::MAX_WIDTH.'px)'],
            ],
            (new PictureSourceTag($this->src))->media(self::MIN_WIDTH, self::MAX_WIDTH)
        );
    }
}
