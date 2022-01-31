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
use Cloudinary\Transformation\Background;
use Cloudinary\Transformation\Fps;
use Cloudinary\Transformation\KeyframeInterval;
use Cloudinary\Transformation\Timeline;
use InvalidArgumentException;

/**
 * Class VideoAudioTest
 */
final class VideoAudioTest extends UnitTestCase
{
    public function testAudioFrequency()
    {
        $af = [8000, 11025, 16000, 22050, 32000, 37800, 44056, 44100, 47250, 48000, 88200, 96000, 176400, 192000];

        foreach ($af as $audioFrequency) {
            self::assertEquals(
                "af_$audioFrequency",
                (string)AudioFrequency::{'freq' . $audioFrequency}()
            );
        }
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
            'eo_3.22,so_2.67'   => [2.67, 3.22],
            'eo_70p,so_35p'     => ['35%', '70%'],
            'eo_71p,so_36p'     => ['36p', '71p'],
            'eo_70.5p,so_35.5p' => ['35.5p', '70.5p'],
            'eo_70.5p,so_auto'  => ['auto', '70.5p'],
            'du_10p,so_auto'    => ['auto', null, '10%'],
        ];

        foreach ($ranges as $transformation => $range) {
            self::assertEquals(
                $transformation,
                (string)Timeline::position(...$range)
            );
        }

        self::assertEquals(
            'eo_3.21,so_2.66',
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

        self::assertEquals(
            'fps_22-24',
            (string)(new Fps())->min(22)->max(24)
        );
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


    /**
     * Data provider for testExceptionsKeyframeInterval().
     *
     * @return array[]
     */
    public function exceptionsKeyframeIntervalDataProvider()
    {
        return [
            [
                'exceptionClass' => InvalidArgumentException::class,
                'exceptionMessage' => 'No more than 1 argument is expected',
                'args' => [1, 2]
            ],
            [
                'exceptionClass' => InvalidArgumentException::class,
                'exceptionMessage' => "'-1' should be greater than zero",
                'args' => [-1],
            ],
            [
                'exceptionClass' => InvalidArgumentException::class,
                'exceptionMessage' => "Argument should be a number or a string",
                'args' => [['a' => 2]],
            ],
        ];
    }

    /**
     * @dataProvider exceptionsKeyframeIntervalDataProvider
     *
     * @param string $exceptionClass
     * @param string $exceptionMessage
     * @param array  $args
     */
    public function testExceptionsKeyframeInterval($exceptionClass, $exceptionMessage, $args)
    {
        $this->expectException($exceptionClass);
        $this->expectExceptionMessage($exceptionMessage);

        new KeyframeInterval(...$args);
    }
}
