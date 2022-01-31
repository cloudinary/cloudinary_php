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

use Cloudinary\Tag\JsConfigTag;

/**
 * Class JsConfigTagTest
 */
final class JsConfigTagTest extends TagTestCase
{
    public function testClientHintsMetaTag()
    {
        $tag = new JsConfigTag();

        self::assertEquals(
            implode(
                "\n",
                [
                    '<script type="text/javascript">',
                    '$.cloudinary.config({"api_key":"' . self::API_KEY . '","cloud_name":"' . self::CLOUD_NAME . '"});',
                    '</script>',
                ]
            ),
            (string)$tag
        );
    }
}
