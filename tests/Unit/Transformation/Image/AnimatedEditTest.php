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

use Cloudinary\Test\Unit\UnitTestCase;
use Cloudinary\Transformation\AnimatedEdit;

/**
 * Class AnimatedEditTest
 */
final class AnimatedEditTest extends UnitTestCase
{
    public function testAnimatedEdit()
    {
        self::assertStrEquals(
            'dl_200,e_loop:2',
            (new AnimatedEdit())->delay(200)->loop(2)
        );
        self::assertStrEquals(
            'e_loop',
            (new AnimatedEdit())->loop()
        );
    }
}
