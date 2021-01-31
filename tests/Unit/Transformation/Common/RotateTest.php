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

use Cloudinary\Transformation\Argument\Degree;
use Cloudinary\Transformation\Argument\RotationMode;
use Cloudinary\Transformation\Rotate;
use PHPUnit\Framework\TestCase;

/**
 * Class RotateTest
 */
final class RotateTest extends TestCase
{
    public function testRotate()
    {
        self::assertEquals(
            'a_17',
            (string)Rotate::byAngle(17)
        );

        self::assertEquals(
            'a_17',
            (string)Rotate::byAngle(Degree::byAngle(17))
        );

        self::assertEquals(
            'a_hflip',
            (string)Rotate::horizontalFlip()
        );

        self::assertEquals(
            'a_ignore',
            (string)Rotate::mode(RotationMode::ignore())
        );

        self::assertEquals(
            'a_ignore.17',
            (string)Rotate::mode(RotationMode::IGNORE, 17)
        );

        self::assertEquals(
            'a_90',
            (string)Rotate::byAngle(Degree::deg90())
        );
    }
}
