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

use Cloudinary\Tag\FormInputTag;

/**
 * Class FormInputTagTest
 */
final class FormInputTagTest extends TagTestCase
{
    public function testFormTag()
    {
        $name  = self::ASSET_ID;
        $value = self::IMAGE_NAME;

        $tag = new FormInputTag($name, $value);
        self::assertEquals(
            "<input type=\"hidden\" name=\"$name\" value=\"$value\">",
            (string)$tag
        );
    }
}
