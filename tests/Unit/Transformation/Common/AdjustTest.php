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
        $this->assertEquals(
            "e_blue:$this->adjustLevel",
            (string)Adjust::blue($this->adjustLevel)
        );

        $this->assertEquals(
            "e_blue:$this->adjustNegativeLevel",
            (string)Adjust::blue($this->adjustNegativeLevel)
        );

        $this->assertEquals(
            "e_brightness:$this->adjustLevel",
            (string)Adjust::brightness($this->adjustLevel)
        );

        $this->assertEquals(
            "e_brightness_hsb:$this->adjustLevel",
            (string)Adjust::brightnessHSB($this->adjustLevel)
        );

        $this->assertEquals(
            "e_contrast:$this->adjustLevel",
            (string)Adjust::contrast($this->adjustLevel)
        );

        $this->assertEquals(
            "e_saturation:$this->adjustLevel",
            (string)Adjust::saturation($this->adjustLevel)
        );

        $this->assertEquals(
            'e_recolor:0.3:0.7:0.1:0.3:0.6:0.1:0.2:0.5:0.1',
            (string)Adjust::recolor(0.3, 0.7, 0.1, 0.3, 0.6, 0.1, 0.2, 0.5, 0.1)
        );
    }

    public function testColorAdjustOutOfRange()
    {
        $this->expectException(OutOfRangeException::class);

        Adjust::red(101);
    }


    public function testReplaceColor()
    {
        $this->assertEquals(
            'e_replace_color:green',
            (string)Adjust::replaceColor(Color::GREEN)
        );

        $this->assertEquals(
            'e_replace_color:green:17:red',
            (string)Adjust::replaceColor(Color::GREEN, 17, Color::RED)
        );

        $this->assertEquals(
            'e_replace_color:green:17:red',
            (string)Adjust::replaceColor(Color::GREEN)->from(Color::RED)->tolerance(17)
        );
    }

    public function testHue()
    {
        $this->assertEquals(
            'e_hue:17',
            (string)Adjust::hue(17)
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
        $this->assertEquals(
            'e_gamma:50',
            (string)Adjust::gamma(50)
        );

        $this->assertEquals(
            'e_improve',
            (string)Adjust::improve()
        );

        $this->assertEquals(
            'e_improve:indoor:17',
            (string)Improve::indoor(17)
        );

        $this->assertEquals(
            'e_improve:outdoor:17',
            (string)Adjust::improve(17)->mode(Improve::OUTDOOR)
        );

        $this->assertEquals(
            'e_improve:17',
            (string)new Improve(17)
        );

        $this->assertEquals(
            'e_improve:outdoor',
            (string)Improve::outdoor()
        );

        $this->assertEquals(
            'e_auto_brightness:17',
            (string)Adjust::autoBrightness(17)
        );

        $this->assertEquals(
            'e_auto_color:17',
            (string)Adjust::autoColor(17)
        );

        $this->assertEquals(
            'e_auto_contrast:17',
            (string)Adjust::autoContrast(17)
        );

        $this->assertEquals(
            'e_vibrance:17',
            (string)Adjust::vibrance(17)
        );

        $this->assertEquals(
            'e_viesus_correct',
            (string)new ViesusCorrect()
        );

        $this->assertEquals(
            'e_viesus_correct:no_redeye',
            (string)new ViesusCorrect(ViesusCorrect::NO_REDEYE)
        );

        $this->assertEquals(
            'e_viesus_correct:skin_saturation:17',
            (string)Adjust::viesusCorrect(ViesusCorrect::SKIN_SATURATION)->setEffectValue(17)
        );

        $this->expectException(InvalidArgumentException::class);
        $v = new ViesusCorrect(null, 17);
    }

    public function testFillLight()
    {
        $this->assertEquals(
            'e_fill_light',
            (string)Adjust::fillLight()
        );

        $this->assertEquals(
            'e_fill_light:70',
            (string)Adjust::fillLight(70)
        );

        $this->assertEquals(
            'e_fill_light:70:20',
            (string)Adjust::fillLight(70, 20)
        );
    }

    public function testOpacityAdjustments()
    {
        $this->assertEquals(
            'o_100',
            (string)Adjust::opacity(100)
        );

        $this->assertEquals(
            'e_opacity_threshold:100',
            (string)Adjust::opacityThreshold(100)
        );
    }

    public function testPixelAdjustments()
    {
        $this->assertEquals(
            'e_sharpen:17',
            (string)Adjust::sharpen(17)
        );

        $this->assertEquals(
            'e_unsharp_mask:17',
            (string)Adjust::unsharpMask(17)
        );
    }

    public function testGenericAdjustments()
    {
        $this->assertEquals(
            'e_make_cool:17:19,fl_animated',
            (string)Adjust::generic('make_cool', 17, 19)->setFlag(Flag::animated())
        );
    }
}
