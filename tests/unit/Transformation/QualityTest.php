<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Transformation\Image;

use Cloudinary\Transformation\Chroma;
use Cloudinary\Transformation\Quality;
use Cloudinary\Transformation\QualityParam;
use PHPUnit\Framework\TestCase;

/**
 * Class SampleTest
 */
final class QualityTest extends TestCase
{
    public function testQualityParam()
    {
        $qp = (new QualityParam(17))->chromaSubSampling(Chroma::C420);

        self::assertEquals('q_17:420', (string)$qp);
    }

    public function testQuality()
    {
        $q = (new Quality(17))->chromaSubSampling(Chroma::C420);

        self::assertEquals('q_17:420', (string)$q);

        $qp = (new QualityParam(17))->chromaSubSampling(Chroma::C420);

        $q = new Quality($qp);

        self::assertEquals('q_17:420', (string)$q);
    }

    public function testAutoQuality()
    {
        $aq = Quality::auto();

        self::assertEquals('q_auto', (string)$aq);

        $aqe = Quality::eco();

        self::assertEquals('q_auto:eco', (string)$aqe);

        $aqec = Quality::eco()->chromaSubSampling(Chroma::C422);

        self::assertEquals('q_auto:eco:422', (string)$aqec);

        $aqeaf = Quality::eco()->anyFormat();

        self::assertEquals('fl_any_format,q_auto:eco', (string)$aqeaf);
    }
}
