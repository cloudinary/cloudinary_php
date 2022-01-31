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

use Cloudinary\Transformation\CornerRadius;
use Cloudinary\Transformation\CutByImage;
use Cloudinary\Transformation\Position;
use Cloudinary\Transformation\RoundCorners;
use PHPUnit\Framework\TestCase;

/**
 * Class ImageShapeTest
 */
final class ImageShapeTest extends TestCase
{
    public function testImageRoundCorners()
    {
        self::assertEquals(
            'r_max',
            (string)RoundCorners::max()
        );

        self::assertEquals(
            'r_1:3:0:7',
            (string)RoundCorners::byRadius(1, 3, 0, 7)
        );

        self::assertEquals(
            'r_1:3:5',
            (string)RoundCorners::byRadius(1, 3, 5)
        );

        self::assertEquals(
            'r_1',
            (string)RoundCorners::byRadius(1)
        );

        $cr = new RoundCorners(17);

        $cr->topRight(2)->topLeft(1);
        self::assertEquals(
            'r_1:2',
            (string)$cr
        );

        $cr->topLeft(3)->topRight(4)->bottomLeft(9)->bottomRight(8);
        self::assertEquals(
            'r_3:4:8:9',
            (string)$cr
        );

        self::assertEquals(
            'r_1:2',
            (string)RoundCorners::byRadius(2)->setRadius(CornerRadius::byRadius(1)->topRight(2))
        );

        self::assertEquals(
            'r_1:2',
            (string)RoundCorners::byRadius(1)->setSymmetricRadius(1, 2)
        );

        self::assertEquals(
            'r_1:2:3',
            (string)RoundCorners::byRadius(1)->setPartiallySymmetricRadius(1, 2, 3)
        );
    }

    public function testImageCutByImage()
    {
        self::assertEquals(
            'l_sample/fl_cutter,fl_layer_apply,g_south,y_20',
            (string)(new CutByImage('sample'))->position(Position::south()->offsetY(20))
        );
    }
}
