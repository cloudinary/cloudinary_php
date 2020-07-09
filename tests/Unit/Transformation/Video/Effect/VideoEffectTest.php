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
use Cloudinary\Transformation\Volume;
use PHPUnit\Framework\TestCase;

/**
 * Class VideoEffectTest
 */
final class VideoEffectTest extends TestCase
{
    public function testPreview()
    {
        $preview = Effect::preview(17);

        $this->assertEquals(
            'e_preview:duration_17',
            (string)$preview
        );
    }

    public function testFade()
    {
        $fadeIn = Effect::fadeIn(2000);

        $this->assertEquals(
            'e_fade:2000',
            (string)$fadeIn
        );

        $fadeOut = Effect::fadeOut(2000);

        $this->assertEquals(
            'e_fade:-2000',
            (string)$fadeOut
        );
    }

    public function testVolume()
    {
        $volume = new Volume(99);

        $this->assertEquals(
            'e_volume:99',
            (string)$volume
        );

        $this->assertEquals(
            'e_volume:mute',
            (string)$volume->mute()
        );

        $this->assertEquals(
            'e_volume:-10dB',
            (string)$volume->offset(-10)
        );

        $this->assertEquals(
            'e_volume:0dB',
            (string)$volume->offset(0)
        );

        $this->assertEquals(
            'e_volume:+10dB',
            (string)$volume->offset(10)
        );

        $this->assertEquals(
            'e_volume:400',
            (string)$volume->relative(400)
        );

        $this->assertEquals(
            'e_volume:99',
            (string)Effect::volume(99)
        );

        $this->assertEquals(
            'e_volume:400',
            (string)Effect::volume($volume)
        );
    }
}
