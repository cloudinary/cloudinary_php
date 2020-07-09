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
use Cloudinary\Transformation\Rotate;
use PHPUnit\Framework\TestCase;

/**
 * Class RotateTest
 */
final class RotateTest extends TestCase
{
    public function testRotate()
    {
        $this->assertEquals(
            'a_17',
            (string)Rotate::angle(17)
        );

        $this->assertEquals(
            'a_17',
            (string)Rotate::angle(Degree::angle(17))
        );

        $this->assertEquals(
            'a_hflip',
            (string)Rotate::horizontalFlip()
        );

        $this->assertEquals(
            'a_ignore.17',
            (string)Rotate::angle(Degree::IGNORE, 17)
        );

        $this->assertEquals(
            'a_90',
            (string)Rotate::angle(Degree::deg90())
        );
    }
}
