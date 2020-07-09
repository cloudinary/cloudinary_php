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
use Cloudinary\Transformation\Argument\Gradient;
use Cloudinary\Transformation\Argument\GradientDirection;
use Cloudinary\Transformation\AutoBackground;
use Cloudinary\Transformation\Background;
use Cloudinary\Transformation\CompassGravity;
use Cloudinary\Transformation\CropMode;
use Cloudinary\Transformation\FocalGravity;
use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\Palette;
use Cloudinary\Transformation\Parameter\Misc\BreakpointsJson;
use Cloudinary\Transformation\Position;
use Cloudinary\Transformation\Rotate;
use Cloudinary\Transformation\RoundCorners;
use Cloudinary\Utils;
use PHPUnit\Framework\TestCase;

/**
 * Class ParameterTest
 */
final class ParameterTest extends TestCase
{
    public function testGravity()
    {
        $no_gravity = new CompassGravity();

        $this->assertEquals(
            '',
            (string)$no_gravity
        );

        $this->assertEquals(
            'g_west',
            (string)CompassGravity::west()
        );
    }

    public function testAutoGravity()
    {
        $autoGravity = Gravity::auto();

        $this->assertEquals(
            'g_auto',
            (string)$autoGravity
        );
        $autoFaces = Gravity::auto(FocalGravity::FACES);

        $this->assertEquals(
            'g_auto:faces',
            (string)$autoFaces
        );

        $adv_faces = Gravity::auto(FocalGravity::ADVANCED_FACES);

        $this->assertEquals(
            'g_auto:adv_faces',
            (string)$adv_faces
        );
    }

    public function testGravityWithFallback()
    {
        $this->assertEquals(
            'g_body:face',
            (string)Gravity::body(FocalGravity::FACE)
        );

        $this->assertEquals(
            'g_face:center',
            (string)Gravity::face(CompassGravity::CENTER)
        );
    }

    public function testXYCenterPosition()
    {
        $this->assertEquals(
            'g_xy_center,x_17,y_19',
            (string)Position::xyCenter(17, 19)
        );
    }

    public function testBackground()
    {
        $this->assertEquals(
            'b_red',
            (string)Background::color(Color::RED)
        );
    }

    public function testAutoBackground()
    {
        $this->assertEquals(
            'b_auto:predominant_gradient:2:diagonal_desc',
            (string)AutoBackground::gradientFade(Gradient::PREDOMINANT_GRADIENT, 2, GradientDirection::DIAGONAL_DESC)
        );

        $this->assertEquals(
            'b_auto:predominant_gradient:2:palette_magenta_yellow_black',
            (string)AutoBackground::gradientFade(
                Gradient::PREDOMINANT_GRADIENT,
                2,
                new Palette(['cyan', 'magenta', 'yellow', 'black'])
            )
        );
    }

    public function testBreakpointsJson()
    {
        $this->assertEquals(
            'w_auto:breakpoints_' .
            ResponsiveBreakpointsConfig::DEFAULT_MIN_WIDTH . '_' .
            ResponsiveBreakpointsConfig::DEFAULT_MAX_WIDTH . '_' .
            Utils::bytesToKB(ResponsiveBreakpointsConfig::DEFAULT_BYTES_STEP) . '_' .
            ResponsiveBreakpointsConfig::DEFAULT_MAX_IMAGES . ':json',
            (string)new BreakpointsJson()
        );
    }

    public function testCropMode()
    {
        $this->assertEquals(
            'c_scale',
            (string)new CropMode('scale')
        );
    }

    public function testGenericAction()
    {
        $genericAction = 'c_crop,e_sepia,f_auto,g_auto';

        $this->assertEquals(
            $genericAction,
            (string)Action::generic($genericAction)
        );

        $this->assertEquals(
            "a_auto_left,$genericAction,r_max",
            (string)Action::generic($genericAction)->addParameter(RoundCorners::max()->addParameter(Rotate::autoLeft()))
        );
    }
}
