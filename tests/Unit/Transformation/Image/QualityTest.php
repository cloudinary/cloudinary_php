<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Transformation\Image;

use Cloudinary\Transformation\ChromaSubSampling;
use Cloudinary\Transformation\Delivery;
use Cloudinary\Transformation\JpegMini;
use Cloudinary\Transformation\Qualifier\Dimensions\Dpr;
use Cloudinary\Transformation\Quality;
use Cloudinary\Transformation\QualityQualifier;
use PHPUnit\Framework\TestCase;

/**
 * Class QualityTest
 */
final class QualityTest extends TestCase
{
    public function testQualityParam()
    {
        $qp = (new QualityQualifier(17))->chromaSubSampling(ChromaSubSampling::chroma420());

        self::assertEquals('q_17:420', (string)$qp);
    }

    public function testQuality()
    {
        $q = (new Quality(17))->chromaSubSampling(ChromaSubSampling::chroma420());

        self::assertEquals('q_17:420', (string)$q);

        $qp = (new QualityQualifier(17))->chromaSubSampling(ChromaSubSampling::chroma420());

        $q = new Quality($qp);

        self::assertEquals('q_17:420', (string)$q);
    }

    public function testAutoQuality()
    {
        $aq = Quality::auto();

        self::assertEquals('q_auto', (string)$aq);

        $aqe = Quality::autoEco();

        self::assertEquals('q_auto:eco', (string)$aqe);

        $aqec = Quality::autoEco()->chromaSubSampling(ChromaSubSampling::chroma422());

        self::assertEquals('q_auto:eco:422', (string)$aqec);

        $aqeaf = Quality::autoEco()->anyFormat();

        self::assertEquals('fl_any_format,q_auto:eco', (string)$aqeaf);

        $qjm = Quality::jpegmini();

        self::assertEquals('q_jpegmini', (string)$qjm);

        $qjm = Quality::jpegmini(JpegMini::HIGH);

        self::assertEquals('q_jpegmini:1', (string)$qjm);

        self::assertEquals('q_jpegmini:0', (string) Quality::jpegminiBest());

        self::assertEquals('q_auto:best', (string)Delivery::quality(Quality::autoBest()));
    }

    public function testDpr()
    {
        self::assertEquals('dpr_1.5', (string)Delivery::dpr(1.5));
        self::assertEquals('dpr_auto', (string)Delivery::dpr(Dpr::auto()));
    }

    public function testDensity()
    {
        self::assertEquals('dn_1.5', (string)Delivery::density(1.5));
    }
}
