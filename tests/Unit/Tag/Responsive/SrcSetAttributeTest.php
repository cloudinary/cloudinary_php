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
use Cloudinary\Tag\SrcSet;

/**
 * Class SrcSetAttributeTest
 */
final class SrcSetAttributeTest extends TagTestCase
{
    protected $prefix = 'https://res.cloudinary.com/test123/image/upload/c_scale,w_';

    public function testSrcSetAttribute()
    {
        $srcset = new SrcSet(self::IMAGE_NAME, Configuration::instance());
        $image  = Image::upload(self::IMAGE_NAME, Configuration::instance());

        self::assertStrEquals($image, $srcset);
    }

    public function testSrcSetAttributeWithAutoBreakpoints()
    {
        $c = new Configuration(Configuration::instance());

        $c->responsiveBreakpoints->autoOptimalBreakpoints = true;

        $srcset = new SrcSet(self::IMAGE_NAME, $c);

        self::assertStrEquals(
            "{$this->prefix}828/sample.png 828w, " .
            "{$this->prefix}1366/sample.png 1366w, " .
            "{$this->prefix}1536/sample.png 1536w, " .
            "{$this->prefix}1920/sample.png 1920w, " .
            "{$this->prefix}3840/sample.png 3840w",
            $srcset
        );
    }

    public function testSrcSetAttributeWithCustomBreakpoints()
    {
        $c = new Configuration(Configuration::instance());

        $c->responsiveBreakpoints->breakpoints = [500, 1000, 1500];

        $srcset = new SrcSet(self::IMAGE_NAME, $c);

        self::assertStrEquals(
            "{$this->prefix}500/sample.png 500w, " .
            "{$this->prefix}1000/sample.png 1000w, " .
            "{$this->prefix}1500/sample.png 1500w",
            $srcset
        );
    }
}
