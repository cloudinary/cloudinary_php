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

use Cloudinary\Transformation\ArtisticFilter;
use Cloudinary\Transformation\Cartoonify;
use Cloudinary\Transformation\Argument\Color;
use Cloudinary\Transformation\Effect;
use Cloudinary\Transformation\GradientFade;
use Cloudinary\Transformation\Argument\PointValue;
use Cloudinary\Transformation\OrderedDither;
use Cloudinary\Transformation\Parameter;
use Cloudinary\Transformation\PixelEffect;
use Cloudinary\Transformation\Region;
use Cloudinary\Transformation\Reshape;
use Cloudinary\Transformation\SimulateColorBlind;
use OutOfRangeException;
use PHPUnit\Framework\TestCase;

/**
 * Class SampleTest
 */
final class EffectTest extends TestCase
{
    protected $effectLevel         = 17;
    protected $effectNegativeLevel = -17;

    public function testColorEffects()
    {
        $this->assertEquals(
            'e_sepia',
            (string)Effect::sepia()
        );

        $this->assertEquals(
            "e_sepia:$this->effectLevel",
            (string)Effect::sepia($this->effectLevel)
        );

        $this->assertEquals(
            "e_blackwhite:$this->effectLevel",
            (string)Effect::blackWhite($this->effectLevel)
        );

        $this->assertEquals(
            'e_grayscale',
            (string)Effect::grayscale()
        );

        $this->assertEquals(
            'e_negate',
            (string)Effect::negate()
        );

        $this->assertEquals(
            'e_assist_colorblind',
            (string)Effect::assistColorBlind()
        );

        $this->assertEquals(
            'e_assist_colorblind:8',
            (string)Effect::assistColorBlind(8)
        );

        $this->assertEquals(
            'e_assist_colorblind:8',
            (string)Effect::assistColorBlind()->stripeStrength(8)
        );

        $this->assertEquals(
            'e_assist_colorblind:xray',
            (string)Effect::assistColorBlind()->xRay()
        );

        $this->assertEquals(
            'e_simulate_colorblind:rod_monochromacy',
            (string)Effect::simulateColorBlind(SimulateColorBlind::ROD_MONOCHROMACY)
        );
    }

    public function testColorEffectOutOfRange()
    {
        $this->expectException(OutOfRangeException::class);

        Effect::sepia(101);
    }

    public function testColorize()
    {
        $this->assertEquals(
            'e_colorize',
            (string)Effect::colorize()
        );
        $this->assertEquals(
            'e_colorize:17',
            (string)Effect::colorize(17)
        );

        $this->assertEquals(
            'co_red,e_colorize:17',
            (string)Effect::colorize(17, Color::RED)
        );

        $this->assertEquals(
            'co_red,e_colorize:17',
            (string)Effect::colorize(17)->color(Color::RED)
        );

        $this->assertEquals(
            'co_red,e_colorize:17',
            (string)Effect::colorize(17, Color::BLUE)->color(Color::RED)
        );
    }

    public function testPixelEffects()
    {
        $this->assertEquals(
            'e_pixelate',
            (string)Effect::pixelate()
        );

        $this->assertEquals(
            'e_pixelate_faces',
            (string)Effect::pixelateFaces()
        );

        $this->assertEquals(
            'e_pixelate_region',
            (string)Effect::pixelateRegion()
        );

        $this->assertEquals(
            'e_pixelate_region:17',
            (string)Effect::pixelateRegion(17)
        );

        $this->assertEquals(
            'e_pixelate_region:17,x_10,y_20',
            (string)Effect::pixelateRegion(17, 10, 20)
        );

        $this->assertEquals(
            'e_pixelate_region:17,h_40,w_30,x_10,y_20',
            (string)Effect::pixelateRegion(17, 10, 20, 30, 40)
        );

        $this->assertEquals(
            'e_pixelate_region:17,h_40,w_30,x_10,y_20',
            (string)Effect::pixelateRegion(17)->region(new Region(10, 20, 30, 40))
        );

        $this->assertEquals(
            'e_pixelate_region:17,h_40,y_20',
            (string)Effect::pixelateRegion(17)->y(20)->height(40)
        );

        $this->assertEquals(
            'e_pixelate_region:17,h_40,w_30,x_10,y_20',
            (string)Effect::pixelateRegion(17)->size('30x40')->position(10, 20)
        );

        $this->assertEquals(
            'e_vignette:17',
            (string)PixelEffect::vignette(17)
        );

        $this->assertEquals(
            'e_blur:17',
            (string)Effect::blur(17)
        );

        $this->assertEquals(
            'e_blur_region:2000,h_40,w_30,x_10,y_20',
            (string)Effect::blurRegion(2000)->size('30x40')->position(10, 20)
        );

        $this->assertEquals(
            'e_blur_faces:17',
            (string)Effect::blurFaces(17)
        );

        $this->assertEquals(
            'e_make_transparent:17',
            (string)PixelEffect::makeTransparent(17)
        );
    }

    public function testOrderedDither()
    {
        $this->assertEquals(
            'e_ordered_dither:17',
            (string)new OrderedDither(17)
        );

        $this->assertEquals(
            'e_ordered_dither:17',
            (string)new OrderedDither(OrderedDither::CIRCLES_7X7_BLACK)
        );

        $this->assertEquals(
            'e_ordered_dither:5',
            (string)Effect::orderedDither(OrderedDither::ORDERED_8X8_DISPERSED)
        );

        $this->expectException(OutOfRangeException::class);

        new OrderedDither(19);
    }

    public function testShadow()
    {
        $this->assertEquals(
            'co_green,e_shadow:17,x_30,y_40',
            (string)Effect::shadow(17)->position(30, 40)->color(Color::GREEN)
        );
    }

    public function testTrimImageEffect()
    {
        $this->assertEquals(
            'e_trim:17:blue',
            (string)Reshape::trim(17)->colorOverride(Color::BLUE)
        );
    }

    public function testGradientFade()
    {
        $this->assertEquals(
            'e_gradient_fade',
            (string)Effect::gradientFade()
        );

        $this->assertEquals(
            'e_gradient_fade:symmetric',
            (string)Effect::gradientFade(GradientFade::SYMMETRIC)
        );

        $this->assertEquals(
            'e_gradient_fade:symmetric:50',
            (string)Effect::gradientFade(50, GradientFade::SYMMETRIC)
        );

        $this->assertEquals(
            'e_gradient_fade:symmetric:50,x_0.3,y_0.15',
            (string)Effect::gradientFade(50, GradientFade::SYMMETRIC)->x(0.3)->y(0.15)
        );
    }

    public function testDistortArc()
    {
        $this->assertEquals(
            'e_distort:arc:17',
            (string)Reshape::distortArc(17)
        );

        $this->assertEquals(
            'e_distort:arc:-17',
            (string)Reshape::distortArc(-17)
        );

        $this->expectException(OutOfRangeException::class);

        Reshape::distortArc(361);
    }

    public function testDistort()
    {
        $this->assertEquals(
            'e_distort:1:1:1:99:99:99:99:1',
            (string)Reshape::distort(
                new PointValue(1, 1),
                new PointValue(1, 99),
                new PointValue(99, 99),
                new PointValue(99, 1)
            )
        );
    }

    public function testShear()
    {
        $this->assertEquals(
            'e_shear:30:60',
            (string)Reshape::shear(30, 60)
        );

        $this->assertEquals(
            'e_shear:30:60',
            (string)Reshape::shear(30)->skewY(60)
        );

        $this->assertEquals(
            'e_shear:30:60',
            (string)Reshape::shear()->skewX(30)->skewY(60)
        );
    }


    public function testArtisticFilter()
    {
        $this->assertEquals(
            'e_art:incognito',
            (string)Effect::artisticFilter(ArtisticFilter::INCOGNITO)
        );

        $this->assertEquals(
            'e_art:al_dente',
            (string)Effect::artisticFilter(ArtisticFilter::alDente())
        );

        $this->assertEquals(
            'e_art:red_rock',
            (string)Effect::artisticFilter(ArtisticFilter::redRock())
        );

        $this->assertEquals(
            'e_art:custom',
            (string)Effect::artisticFilter('custom')
        );
    }

    public function testCartoonify()
    {
        $this->assertEquals(
            'e_cartoonify:17:19',
            (string)new Cartoonify(17, 19)
        );

        $this->assertEquals(
            'e_cartoonify:17:19',
            (string)Effect::cartoonify(17, 19)
        );

        $this->assertEquals(
            'e_cartoonify:17:19',
            (string)Effect::cartoonify()->lineStrength(17)->colorReduction(19)
        );

        $this->assertEquals(
            'e_cartoonify:17:bw',
            (string)Effect::cartoonify()->lineStrength(17)->blackWhite()
        );
    }

    public function testMiscEffects()
    {
        $this->assertEquals(
            'e_adv_redeye',
            (string)Effect::advancedRedEye()
        );

        $this->assertEquals(
            'e_redeye',
            (string)Effect::redEye()
        );

        $this->assertEquals(
            'e_oil_paint:17',
            (string)Effect::oilPaint(17)
        );

        $this->assertEquals(
            'e_style_transfer,l_lighthouse',
            (string)Effect::styleTransfer('lighthouse')
        );

        $this->assertEquals(
            'e_style_transfer:40,l_lighthouse',
            (string)Effect::styleTransfer('lighthouse', 40)
        );

        $this->assertEquals(
            'e_style_transfer:40,l_lighthouse',
            (string)Effect::styleTransfer('lighthouse', 40, false)
        );

        $this->assertEquals(
            'e_style_transfer:preserve_color:40,l_lighthouse',
            (string)Effect::styleTransfer('lighthouse', 40, true)
        );

        $this->assertEquals(
            'e_vectorize:colors:2:corners:25:despeckle:50:detail:0.5:paths:100',
            (string)Effect::vectorize()->colors(2)->detail(0.5)->despeckle(50)->corners(25)->paths(100)
        );
    }

    public function testPlaybackEffects()
    {
        $this->assertEquals(
            'e_loop',
            (string)Effect::loop()
        );

        $this->assertEquals(
            'e_loop:17',
            (string)Effect::loop(17)
        );

        $this->assertEquals(
            'e_accelerate:100',
            (string)Effect::accelerate(100)
        );

        $this->assertEquals(
            'e_boomerang',
            (string)Effect::boomerang()
        );

        $this->assertEquals(
            'e_reverse',
            (string)Effect::reverse()
        );
    }

    public function testGenericEffects()
    {
        $this->assertEquals(
            'co_orange,e_outline:outer:15:200',
            (string)Effect::generic('outline:outer', 15, 200)->addParameter(Parameter::generic('co', 'orange'))
        );

        $this->assertEquals(
            'e_outline:outer:15:200',
            (string)Effect::fromParams(['outline', 'outer', 15, 200])
        );

        $this->assertEquals(
            'e_sepia',
            (string)Effect::fromParams('sepia')
        );
    }
}
