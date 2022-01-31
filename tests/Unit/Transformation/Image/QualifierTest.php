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

use Cloudinary\Configuration\ResponsiveBreakpointsConfig;
use Cloudinary\Transformation\Action;
use Cloudinary\Transformation\Argument\Color;
use Cloudinary\Transformation\Argument\GradientDirection;
use Cloudinary\Transformation\Background;
use Cloudinary\Transformation\Compass;
use Cloudinary\Transformation\CompassGravity;
use Cloudinary\Transformation\CropMode;
use Cloudinary\Transformation\FocalGravity;
use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\Palette;
use Cloudinary\Transformation\Position;
use Cloudinary\Transformation\Qualifier\Misc\BreakpointsJson;
use Cloudinary\Transformation\Rotate;
use Cloudinary\Transformation\RoundCorners;
use Cloudinary\Utils;
use PHPUnit\Framework\TestCase;

/**
 * Class ParameterTest
 */
final class QualifierTest extends TestCase
{
    public function testGravity()
    {
        $no_gravity = new CompassGravity();

        self::assertEquals(
            '',
            (string)$no_gravity
        );

        self::assertEquals(
            'g_west',
            (string)CompassGravity::west()
        );
    }

    public function testAutoGravity()
    {
        $autoGravity = Gravity::auto();

        self::assertEquals(
            'g_auto',
            (string)$autoGravity
        );
        $autoFaces = Gravity::auto(FocalGravity::FACES);

        self::assertEquals(
            'g_auto:faces',
            (string)$autoFaces
        );

        $adv_faces = Gravity::auto(FocalGravity::ADVANCED_FACES);

        self::assertEquals(
            'g_auto:adv_faces',
            (string)$adv_faces
        );
    }

    public function testGravityWithFallback()
    {
        self::assertEquals(
            'g_body:face',
            (string)Gravity::body(FocalGravity::FACE)
        );

        self::assertEquals(
            'g_face:center',
            (string)Gravity::face(Compass::center())
        );
    }

    public function testXYCenterPosition()
    {
        self::assertEquals(
            'g_xy_center,x_17,y_19',
            (string)Position::xyCenter(17, 19)
        );
    }

    public function testBackground()
    {
        self::assertEquals(
            'b_red',
            (string)Background::color(Color::RED)
        );
    }

    public function testAutoBackground()
    {
        self::assertEquals(
            'b_auto:predominant_gradient:2:diagonal_desc',
            (string)Background::predominantGradient(2, GradientDirection::DIAGONAL_DESC)
        );

        self::assertEquals(
            'b_auto:predominant_gradient:2:palette_cyan_magenta_yellow_black',
            (string)Background::predominantGradient(2)->palette(new Palette('cyan', 'magenta', 'yellow', 'black'))
        );
    }

    public function testBreakpointsJson()
    {
        self::assertEquals(
            'w_auto:breakpoints_' .
            ResponsiveBreakpointsConfig::DEFAULT_MIN_WIDTH . '_' .
            ResponsiveBreakpointsConfig::DEFAULT_MAX_WIDTH . '_' .
            Utils::bytesToKB(BreakpointsJson::DEFAULT_BYTES_STEP) . '_' .
            ResponsiveBreakpointsConfig::DEFAULT_MAX_IMAGES . ':json',
            (string)new BreakpointsJson()
        );
    }

    public function testCropMode()
    {
        self::assertEquals(
            'c_scale',
            (string)new CropMode('scale')
        );
    }

    public function testGenericAction()
    {
        $genericAction = 'c_crop,e_sepia,f_auto,g_auto';

        self::assertEquals(
            $genericAction,
            (string)Action::generic($genericAction)
        );

        self::assertEquals(
            "a_auto_left,$genericAction,r_max",
            (string)Action::generic($genericAction)->addQualifier(RoundCorners::max()->addQualifier(Rotate::autoLeft()))
        );
    }
}
