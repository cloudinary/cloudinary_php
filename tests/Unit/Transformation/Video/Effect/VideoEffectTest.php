<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Transformation\Video\Effect;

use Cloudinary\Transformation\Effect;
use Cloudinary\Transformation\ShakeStrength;
use Cloudinary\Transformation\VideoEdit;
use Cloudinary\Transformation\Volume;
use PHPUnit\Framework\TestCase;

/**
 * Class VideoEffectTest
 */
final class VideoEffectTest extends TestCase
{
    public function testDeshake()
    {
        self::assertEquals(
            'e_deshake',
            (string)Effect::deshake()
        );
        self::assertEquals(
            'e_deshake:32',
            (string)Effect::deshake(32)
        );

        self::assertEquals(
            'e_deshake:32',
            (string)Effect::deshake()->shakeStrength(ShakeStrength::pixels32())
        );
    }

    public function testFade()
    {
        $fadeIn = Effect::fadeIn();

        self::assertEquals(
            'e_fade',
            (string)$fadeIn
        );

        $fadeIn = Effect::fadeIn()->duration(2000);

        self::assertEquals(
            'e_fade:2000',
            (string)$fadeIn
        );

        $fadeOut = Effect::fadeOut(2000);

        self::assertEquals(
            'e_fade:-2000',
            (string)$fadeOut
        );

        $fadeOut = Effect::fadeOut()->duration(2000);

        self::assertEquals(
            'e_fade:-2000',
            (string)$fadeOut
        );
    }

    public function testVolume()
    {
        self::assertEquals(
            'e_volume:99',
            (string)Volume::volume(99)
        );

        self::assertEquals(
            'e_volume:mute',
            (string)Volume::mute()
        );

        self::assertEquals(
            'e_volume:-10dB',
            (string)Volume::byDecibels(-10)
        );

        self::assertEquals(
            'e_volume:0dB',
            (string)Volume::byDecibels(0)
        );

        self::assertEquals(
            'e_volume:+10dB',
            (string)Volume::byDecibels(10)
        );

        self::assertEquals(
            'e_volume:400',
            (string)Volume::byPercent(400)
        );

        self::assertEquals(
            'e_volume:99',
            (string)VideoEdit::volume(99)
        );

        self::assertEquals(
            'e_volume:400',
            (string)VideoEdit::volume(new Volume(400))
        );
    }
}
