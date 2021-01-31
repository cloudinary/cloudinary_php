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

use Cloudinary\Tag\ClientHintsMetaTag;

/**
 * Class MetaClientHintsTest
 */
final class ClientHintsMetaTagTest extends TagTestCase
{
    public function testClientHintsMetaTag()
    {
        $tag = new ClientHintsMetaTag();
        self::assertTagAttributeEquals('DPR, Viewport-Width, Width', $tag, 'content');
        self::assertTagAttributeEquals('Accept-CH', $tag, 'http-equiv');
    }
}
