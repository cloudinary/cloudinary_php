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

use Cloudinary\Transformation\AnimatedFormat;
use Cloudinary\Transformation\Delivery;
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\FormatQualifier;
use Cloudinary\Transformation\Progressive;
use PHPUnit\Framework\TestCase;

/**
 * Class FormatTest
 */
final class FormatTest extends TestCase
{
    public function testFormatParam()
    {
        $fp = (new FormatQualifier(Format::FLIF));

        self::assertEquals('f_flif', (string)$fp);
    }

    public function testFormat()
    {
        $f = new Format(Format::FLIF);

        self::assertEquals('f_flif', (string)$f);

        $ifb = Format::flif();

        self::assertEquals('f_flif', (string)$ifb);

        $vfb = Format::videoMp4();

        self::assertEquals('f_mp4', (string)$vfb);

        $ifb = Format::audioAiff();

        self::assertEquals('f_aiff', (string)$ifb);
    }

    public function testFormatMethod()
    {
        $f = new Format(Format::JPG);
        $f->format(Format::GIF);

        self::assertEquals('f_gif', (string)$f);
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

        $ffl->progressive(Progressive::steep())->preserveTransparency();

        self::assertEquals('f_auto,fl_lossy,fl_preserve_transparency,fl_progressive:steep', (string)$ffl);
    }

    public function testAnimatedFormat()
    {
        self::assertEquals('f_auto,fl_animated', (string)AnimatedFormat::auto());
        self::assertEquals('f_gif,fl_animated', (string)AnimatedFormat::gif());
        self::assertEquals('f_webp,fl_animated,fl_awebp', (string)AnimatedFormat::webp());
        self::assertEquals('f_png,fl_animated,fl_apng', (string)AnimatedFormat::png());

        self::assertEquals('f_webp,fl_animated,fl_awebp,fl_lossy', (string)AnimatedFormat::webp()->lossy());
    }
}
