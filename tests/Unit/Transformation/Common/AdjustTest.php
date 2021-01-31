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

use Cloudinary\Transformation\Adjust;
use Cloudinary\Transformation\Argument\Color;
use Cloudinary\Transformation\Flag;
use Cloudinary\Transformation\Improve;
use Cloudinary\Transformation\ImproveMode;
use Cloudinary\Transformation\ViesusCorrect;
use InvalidArgumentException;
use OutOfRangeException;
use PHPUnit\Framework\TestCase;

/**
 * Class AdjustTest
 */
final class AdjustTest extends TestCase
{
    protected $adjustLevel         = 17;
    protected $adjustNegativeLevel = -17;

    public function testColorAdjustments()
    {
        self::assertEquals(
            "e_blue:$this->adjustLevel",
            (string)Adjust::blue($this->adjustLevel)
        );

        self::assertEquals(
            "e_blue:$this->adjustNegativeLevel",
            (string)Adjust::blue($this->adjustNegativeLevel)
        );

        self::assertEquals(
            "e_brightness:$this->adjustLevel",
            (string)Adjust::brightness()->level($this->adjustLevel)
        );

        self::assertEquals(
            "e_brightness_hsb:$this->adjustLevel",
            (string)Adjust::brightnessHSB($this->adjustLevel)
        );

        self::assertEquals(
            "e_contrast:$this->adjustLevel",
            (string)Adjust::contrast($this->adjustLevel)
        );

        self::assertEquals(
            "e_saturation:$this->adjustLevel",
            (string)Adjust::saturation($this->adjustLevel)
        );

        self::assertEquals(
            'e_recolor:0.3:0.7:0.1:0.3:0.6:0.1:0.2:0.5:0.1',
            (string)Adjust::recolor([[0.3, 0.7, 0.1], [0.3, 0.6, 0.1], [0.2, 0.5, 0.1]])
        );

        // flat syntax
        self::assertEquals(
            'e_recolor:0.3:0.7:0.1:0.3:0.6:0.1:0.2:0.5:0.1',
            (string)Adjust::recolor(0.3, 0.7, 0.1, 0.3, 0.6, 0.1, 0.2, 0.5, 0.1)
        );
        self::assertEquals(
            'e_recolor:0.3:0.7:0.1:0.3:0.6:0.1:0.2:0.5:0.1',
            (string)Adjust::recolor([0.3, 0.7, 0.1, 0.3, 0.6, 0.1, 0.2, 0.5, 0.1])
        );

        // With alpha channel
        self::assertEquals(
            'e_recolor:0.1:0.2:0.3:0.1:0.4:0.5:0.6:0.2:0.7:0.8:0.9:0.3:0.7:0.8:0.9:0.4',
            (string)Adjust::recolor(
                [
                    [0.1, 0.2, 0.3, 0.1],
                    [0.4, 0.5, 0.6, 0.2],
                    [0.7, 0.8, 0.9, 0.3],
                    [0.7, 0.8, 0.9, 0.4],
                ]
            )
        );
    }

    public function testColorAdjustOutOfRange()
    {
        $this->expectException(OutOfRangeException::class);

        Adjust::red(101);
    }


    public function testReplaceColor()
    {
        self::assertEquals(
            'e_replace_color:green',
            (string)Adjust::replaceColor(Color::GREEN)
        );

        self::assertEquals(
            'e_replace_color:green:17:red',
            (string)Adjust::replaceColor(Color::GREEN, 17, Color::RED)
        );

        self::assertEquals(
            'e_replace_color:green:17:red',
            (string)Adjust::replaceColor(Color::GREEN)->fromColor(Color::RED)->tolerance(17)
        );

        self::assertEquals(
            'e_replace_color:green1',
            (string)Adjust::replaceColor(Color::GREEN)->toColor(Color::GREEN1)
        );
    }

    public function testHue()
    {
        self::assertEquals(
            'e_hue:17',
            (string)Adjust::hue(17)
        );

        self::assertEquals(
            'e_hue:17',
            (string)Adjust::hue()->level(17)
        );

        $passed = false;
        try {
            $res = (string)Adjust::hue(-101);
        } catch (OutOfRangeException $e) {
            $passed = true;
        }
        self::assertTrue($passed);

        $passed = false;
        try {
            $res = (string)Adjust::hue(101);
        } catch (OutOfRangeException $e) {
            $passed = true;
        }

        self::assertTrue($passed);
    }

    public function testImprovementAdjustments()
    {
        self::assertEquals(
            'e_gamma:50',
            (string)Adjust::gamma(50)
        );

        self::assertEquals(
            'e_gamma:50',
            (string)Adjust::gamma()->level(50)
        );

        self::assertEquals(
            'e_improve',
            (string)Adjust::improve()
        );

        self::assertEquals(
            'e_improve:indoor:17',
            (string)Adjust::improve()->mode(ImproveMode::INDOOR)->blend(17)
        );

        self::assertEquals(
            'e_improve:outdoor:17',
            (string)Adjust::improve(17)->mode(ImproveMode::outdoor())
        );

        self::assertEquals(
            'e_improve:17',
            (string)new Improve(17)
        );

        self::assertEquals(
            'e_improve:outdoor',
            (string)Improve::outdoor()
        );

        self::assertEquals(
            'e_improve:indoor',
            (string)Improve::indoor()
        );

        self::assertEquals(
            'e_auto_brightness:17',
            (string)Adjust::autoBrightness(17)
        );

        self::assertEquals(
            'e_auto_brightness:17',
            (string)Adjust::autoBrightness()->blend(17)
        );

        self::assertEquals(
            'e_auto_color:17',
            (string)Adjust::autoColor(17)
        );

        self::assertEquals(
            'e_auto_color:17',
            (string)Adjust::autoColor()->blend(17)
        );

        self::assertEquals(
            'e_auto_contrast:17',
            (string)Adjust::autoContrast(17)
        );

        self::assertEquals(
            'e_auto_contrast:17',
            (string)Adjust::autoContrast()->blend(17)
        );

        self::assertEquals(
            'e_vibrance:17',
            (string)Adjust::vibrance(17)
        );

        self::assertEquals(
            'e_vibrance:17',
            (string)Adjust::vibrance()->strength(17)
        );

        self::assertEquals(
            'e_viesus_correct',
            (string)new ViesusCorrect()
        );

        self::assertEquals(
            'e_viesus_correct:no_redeye',
            (string)Adjust::viesusCorrect()->noRedEye()
        );

        self::assertEquals(
            'e_viesus_correct:skin_saturation',
            (string)Adjust::viesusCorrect()->skinSaturation()
        );

        self::assertEquals(
            'e_viesus_correct:no_redeye:skin_saturation_17',
            (string)Adjust::viesusCorrect()->skinSaturation(17)->noRedEye()
        );
    }

    public function testFillLight()
    {
        self::assertEquals(
            'e_fill_light',
            (string)Adjust::fillLight()
        );

        self::assertEquals(
            'e_fill_light:70',
            (string)Adjust::fillLight(70)
        );

        self::assertEquals(
            'e_fill_light:70',
            (string)Adjust::fillLight()->blend(70)
        );

        self::assertEquals(
            'e_fill_light:70:20',
            (string)Adjust::fillLight(70, 20)
        );

        self::assertEquals(
            'e_fill_light:70:20',
            (string)Adjust::fillLight()->blend(70)->bias(20)
        );
    }

    public function testOpacityAdjustments()
    {
        self::assertEquals(
            'o_100',
            (string)Adjust::opacity(100)
        );

        self::assertEquals(
            'e_opacity_threshold:100',
            (string)Adjust::opacityThreshold(100)
        );

        self::assertEquals(
            'e_opacity_threshold:100',
            (string)Adjust::opacityThreshold()->level(100)
        );
    }

    public function testPixelAdjustments()
    {
        self::assertEquals(
            'e_sharpen:17',
            (string)Adjust::sharpen(17)
        );

        self::assertEquals(
            'e_sharpen:17',
            (string)Adjust::sharpen()->strength(17)
        );

        self::assertEquals(
            'e_unsharp_mask:17',
            (string)Adjust::unsharpMask(17)
        );

        self::assertEquals(
            'e_unsharp_mask:17',
            (string)Adjust::unsharpMask()->strength(17)
        );
    }

    public function testGenericAdjustments()
    {
        self::assertEquals(
            'e_make_cool:17:19,fl_animated',
            (string)Adjust::generic('make_cool', 17, 19)->setFlag(Flag::animated())
        );
    }
}
