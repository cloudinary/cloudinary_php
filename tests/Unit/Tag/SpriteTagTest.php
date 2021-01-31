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
use Cloudinary\Tag\SpriteTag;

/**
 * Class SpriteTagTest
 */
final class SpriteTagTest extends TagTestCase
{
    public function testSpriteTag()
    {
        $tag = (new SpriteTag(self::IMAGE_NAME));
        self::assertEquals(
            '<link type="text/css" rel="stylesheet" href="https://res.cloudinary.com/test123/image/sprite/'
            . self::ASSET_ID . '.css">',
            (string)$tag
        );
    }

    public function testSpriteTagAttributes()
    {
        $tag = new SpriteTag(self::IMAGE_NAME);
        $image = Image::sprite(self::IMAGE_NAME);

        self::assertTagAttributeEquals('text/css', $tag, 'type');
        self::assertTagAttributeEquals('stylesheet', $tag, 'rel');
        self::assertTagAttributeEquals((string)$image, $tag, 'href');
    }
}
