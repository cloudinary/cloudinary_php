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

use Cloudinary\Transformation\Angle;
use Cloudinary\Transformation\Argument\Degree;
use Cloudinary\Transformation\Argument\RotationMode;
use Cloudinary\Transformation\LayerFlag;
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

        $rotate = Rotate::byAngle(Degree::deg90())->setFlag(LayerFlag::splice())->setFlag(LayerFlag::relative());

        self::assertEquals(
            'a_90,fl_relative,fl_splice',
            (string)$rotate
        );

        $rotate->unsetFlag(LayerFlag::splice());

        self::assertEquals(
            'a_90,fl_relative',
            (string)$rotate
        );
    }


    /**
     * Should create and set an angle.
     */
    public function testAngle()
    {
        $angle = new Angle(5);

        self::assertEquals(
            'a_5',
            (string)$angle
        );

        $angle->setAngle(6);

        self::assertEquals(
            'a_6',
            (string)$angle
        );
    }

    /**
     * The data provider for `testAngleMethods()`.
     *
     * @return array[]
     */
    public function angleMethodsDataProvider()
    {
        return [
            [
                'mode' => 'auto_right',
                'method' => 'autoRight',
            ],
            [
                'mode' => 'auto_left',
                'method' => 'autoLeft',
            ],
            [
                'mode' => 'vflip',
                'method' => 'verticalFlip',
            ],
            [
                'mode' => 'hflip',
                'method' => 'horizontalFlip',
            ],
            [
                'mode' => 'ignore',
                'method' => 'ignore',
            ],
            [
                'mode' => '90',
                'method' => 'deg90',
            ],
            [
                'mode' => '180',
                'method' => 'deg180',
            ],
            [
                'mode' => '270',
                'method' => 'deg270',
            ],
        ];
    }

    /**
     * Should create an angle.
     *
     * @dataProvider angleMethodsDataProvider
     *
     * @param string $mode
     * @param string $method
     */
    public function testAngleMethods($mode, $method)
    {
        self::assertEquals(
            "a_$mode",
            (string)Angle::{$method}()
        );
    }
}
