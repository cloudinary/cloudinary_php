<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Transformation\Common;

use Cloudinary\Transformation\Delivery;
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\FormatParam;
use Cloudinary\Transformation\JpegScanMode;
use PHPUnit\Framework\TestCase;

/**
 * Class FormatTest
 */
final class FormatTest extends TestCase
{
    public function testFormatParam()
    {
        $fp = (new FormatParam(Format::FLIF));

        self::assertEquals('f_flif', (string)$fp);
    }

    public function testFormat()
    {
        $f = new Format(Format::FLIF);

        self::assertEquals('f_flif', (string)$f);

        $ifb = Format::flif();

        self::assertEquals('f_flif', (string)$ifb);

        $vfb = Format::mp4();

        self::assertEquals('f_mp4', (string)$vfb);

        $ifb = Format::aiff();

        self::assertEquals('f_aiff', (string)$ifb);
    }

    public function testAutoFormat()
    {
        $af = Format::auto();

        self::assertEquals('f_auto', (string)$af);

        self::assertEquals('f_auto', (string)Delivery::format(Format::auto()));
    }

    public function testFormatFlags()
    {
        $ffl = Format::auto()->lossy();

        self::assertEquals('f_auto,fl_lossy', (string)$ffl);

        $ffl->progressive(JpegScanMode::STEEP)->preserveTransparency();

        self::assertEquals('f_auto,fl_lossy.preserve_transparency.progressive:steep', (string)$ffl);
    }
}
