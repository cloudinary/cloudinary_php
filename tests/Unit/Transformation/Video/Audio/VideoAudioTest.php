<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Transformation\Video\Audio;

use Cloudinary\Test\Unit\UnitTestCase;
use Cloudinary\Transformation\AudioCodec;
use Cloudinary\Transformation\AudioFrequency;
use Cloudinary\Transformation\Fps;
use Cloudinary\Transformation\KeyframeInterval;
use Cloudinary\Transformation\Timeline;

/**
 * Class VideoAudioTest
 */
final class VideoAudioTest extends UnitTestCase
{
    public function testAudioFrequency()
    {
        self::assertEquals(
            'af_44100',
            (string)AudioFrequency::freq44100()
        );
    }

    public function testAudioCodec()
    {
        $aac = AudioCodec::aac();

        self::assertEquals(
            'ac_aac',
            (string)$aac
        );

        $acNone = AudioCodec::none();

        self::assertEquals(
            'ac_none',
            (string)$acNone
        );
    }

    public function testTimeline()
    {
        $ranges = [
            'so_2.67,eo_3.22'   => [2.67, 3.22],
            'so_35p,eo_70p'     => ['35%', '70%'],
            'so_36p,eo_71p'     => ['36p', '71p'],
            'so_35.5p,eo_70.5p' => ['35.5p', '70.5p'],
            'so_auto,eo_70.5p'  => ['auto', '70.5p'],
            'so_auto,du_10p'    => ['auto', null, '10%'],
        ];

        foreach ($ranges as $transformation => $range) {
            self::assertEquals(
                $transformation,
                (string)Timeline::position(...$range)
            );
        }

        self::assertEquals(
            'so_2.66,eo_3.21',
            (string)Timeline::position()->offset('2.66..3.21')
        );
    }

    public function testVideoFPS()
    {
        $fpsTestValues = [
            ['24-29.97', 'fps_24-29.97'],
            [24, 'fps_24'],
            [24.973, 'fps_24.973'],
            ['24', 'fps_24'],
            ['-24', 'fps_-24'],
            ['$v', 'fps_$v'],
            [[24, 29.97], 'fps_24-29.97'],
            [['24', '$v'], 'fps_24-$v'],
        ];

        foreach ($fpsTestValues as $value) {
            self::assertEquals(
                $value[1],
                (string)Fps::fromParams($value[0])
            );
        }
    }

    public function testKeyframeInterval()
    {
        $testValues = [
            [10, 'ki_10.0'],
            [0.05, 'ki_0.05'],
            [3.45, 'ki_3.45'],
            [300, 'ki_300.0'],
            ['10', 'ki_10'],
            ['$ki', 'ki_$ki'],
        ];

        foreach ($testValues as $value) {
            self::assertEquals(
                $value[1],
                (string)new KeyframeInterval($value[0])
            );
        }
    }
}
