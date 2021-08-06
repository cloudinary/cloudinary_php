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

use Cloudinary\Transformation\Argument\Color;
use Cloudinary\Transformation\Argument\PointValue;
use Cloudinary\Transformation\ArtisticFilter;
use Cloudinary\Transformation\Cartoonify;
use Cloudinary\Transformation\Dither;
use Cloudinary\Transformation\Effect;
use Cloudinary\Transformation\GradientFade;
use Cloudinary\Transformation\PixelEffect;
use Cloudinary\Transformation\Position;
use Cloudinary\Transformation\Qualifier;
use Cloudinary\Transformation\Region;
use Cloudinary\Transformation\Reshape;
use Cloudinary\Transformation\SimulateColorBlind;
use Cloudinary\Transformation\StyleTransfer;
use Cloudinary\Transformation\WhiteBalance;
use Cloudinary\Transformation\Xmp;
use OutOfRangeException;
use PHPUnit\Framework\TestCase;

/**
 * Class SampleTest
 */
final class EffectTest extends TestCase
{
    protected $effectLevel = 17;
    protected $effectNegativeLevel = -17;

    public function testColorEffects()
    {
        self::assertEquals(
            'e_sepia',
            (string)Effect::sepia()
        );

        self::assertEquals(
            "e_sepia:$this->effectLevel",
            (string)Effect::sepia($this->effectLevel)
        );

        self::assertEquals(
            "e_sepia:$this->effectLevel",
            (string)Effect::sepia()->level($this->effectLevel)
        );

        self::assertEquals(
            "e_blackwhite:$this->effectLevel",
            (string)Effect::blackWhite($this->effectLevel)
        );

        self::assertEquals(
            "e_blackwhite:$this->effectLevel",
            (string)Effect::blackWhite()->threshold($this->effectLevel)
        );

        self::assertEquals(
            'e_grayscale',
            (string)Effect::grayscale()
        );

        self::assertEquals(
            'e_negate',
            (string)Effect::negate()
        );

        self::assertEquals(
            'e_assist_colorblind',
            (string)Effect::assistColorBlind()
        );

        self::assertEquals(
            'e_assist_colorblind:8',
            (string)Effect::assistColorBlind(8)
        );

        self::assertEquals(
            'e_assist_colorblind:8',
            (string)Effect::assistColorBlind()->stripesStrength(8)
        );

        self::assertEquals(
            'e_assist_colorblind:xray',
            (string)Effect::assistColorBlind()->xRay()
        );

        self::assertEquals(
            'e_simulate_colorblind:rod_monochromacy',
            (string)Effect::simulateColorBlind(SimulateColorBlind::rodMonochromacy())
        );
    }

    public function testColorEffectOutOfRange()
    {
        $this->expectException(OutOfRangeException::class);

        Effect::sepia(101);
    }

    public function testColorize()
    {
        self::assertEquals(
            'e_colorize',
            (string)Effect::colorize()
        );
        self::assertEquals(
            'e_colorize:17',
            (string)Effect::colorize(17)
        );

        self::assertEquals(
            'co_red,e_colorize:17',
            (string)Effect::colorize(17, Color::RED)
        );

        self::assertEquals(
            'co_red,e_colorize:17',
            (string)Effect::colorize()->level(17)->color(Color::RED)
        );

        self::assertEquals(
            'co_red,e_colorize:17',
            (string)Effect::colorize(17, Color::BLUE)->color(Color::RED)
        );
    }

    public function testPixelateEffects()
    {
        self::assertEquals(
            'e_pixelate',
            (string)Effect::pixelate()
        );

        self::assertEquals(
            'e_pixelate:5',
            (string)Effect::pixelate(5)
        );

        self::assertEquals(
            'e_pixelate:5',
            (string)Effect::pixelate()->squareSize(5)
        );

        self::assertEquals(
            'e_pixelate_faces',
            (string)Effect::pixelate()->region(Region::faces())
        );

        self::assertEquals(
            'e_pixelate_faces:5',
            (string)Effect::pixelate(5)->region(Region::faces())
        );

        self::assertEquals(
            'e_pixelate_faces:5',
            (string)Effect::pixelate()->squareSize(5)->region(Region::faces())
        );

        self::assertEquals(
            'e_pixelate_region',
            (string)Effect::pixelate()->region(Region::custom())
        );

        self::assertEquals(
            'e_pixelate_region:17',
            (string)Effect::pixelate(17)->region(Region::custom())
        );

        self::assertEquals(
            'e_pixelate_region:17,x_10,y_20',
            (string)Effect::pixelate(17)->region(Region::custom(10, 20))
        );

        self::assertEquals(
            'e_pixelate_region:17,h_40,w_30,x_10,y_20',
            (string)Effect::pixelate(17)->region(Region::custom(10, 20, 30, 40))
        );

        self::assertEquals(
            'e_pixelate_region:17,h_40,w_30,x_10,y_20',
            (string)Effect::pixelate(17)->region(new Region(10, 20, 30, 40))
        );

        self::assertEquals(
            'e_pixelate_region:17,h_40,y_20',
            (string)Effect::pixelate(17)->y(20)->height(40)
        );

        self::assertEquals(
            'e_pixelate_region:17,h_40,w_30,x_10,y_20',
            (string)Effect::pixelate(17)->size('30x40')->position(10, 20)
        );

        self::assertEquals(
            'e_pixelate_region:17,g_ocr_text',
            (string)Effect::pixelate(17)->region(Region::ocr())
        );
    }

    public function testBlurEffects()
    {
        self::assertEquals(
            'e_blur:17',
            (string)Effect::blur(17)
        );

        self::assertEquals(
            'e_blur:17',
            (string)Effect::blur()->strength(17)
        );

        self::assertEquals(
            'e_blur_region:2000,h_40,w_30,x_10,y_20',
            (string)Effect::blur(2000)->region(Region::custom()->size('30x40')->position(10, 20))
        );

        self::assertEquals(
            'e_blur_faces:17',
            (string)Effect::blur(17)->region(Region::faces())
        );

        self::assertEquals(
            'e_blur_faces:17',
            (string)Effect::blur()->strength(17)->region(Region::faces())
        );

        self::assertEquals(
            'e_blur_region:17,g_ocr_text',
            (string)Effect::blur(17)->region(Region::ocr())
        );
    }

    public function testPixelEffects()
    {
        self::assertEquals(
            'e_vignette:17',
            (string)PixelEffect::vignette(17)
        );

        self::assertEquals(
            'e_vignette:17',
            (string)Effect::vignette()->strength(17)
        );

        self::assertEquals(
            'co_white,e_make_transparent:17',
            (string)PixelEffect::makeTransparent()->tolerance(17)->colorToReplace(Color::white())
        );

        self::assertEquals(
            'e_bgremoval:screen:aabbcc',
            (string)PixelEffect::removeBackground()->colorToRemove(Color::rgb("#aabbcc"))->screen()
        );

        self::assertEquals(
            'e_bgremoval:red',
            (string)PixelEffect::removeBackground()->colorToRemove(Color::red())
        );
    }

    public function testDither()
    {
        self::assertEquals(
            'e_ordered_dither:17',
            (string)new Dither(Dither::circles7x7Black())
        );

        self::assertEquals(
            'e_ordered_dither:17',
            (string)new Dither(Dither::CIRCLES_7X7_BLACK)
        );

        self::assertEquals(
            'e_ordered_dither:5',
            (string)Effect::dither()->type(Dither::ordered8x8Dispersed())
        );

        $this->expectException(OutOfRangeException::class);

        new Dither(19);
    }

    public function testShadow()
    {
        self::assertEquals(
            'co_green,e_shadow:17,x_30,y_40',
            (string)Effect::shadow()->strength(17)->offset(30, 40)->color(Color::GREEN)
        );
    }

    public function testTrimImageEffect()
    {
        self::assertEquals(
            'e_trim:17:blue',
            (string)Reshape::trim(17)->colorOverride(Color::BLUE)
        );
    }

    public function testCutOutImageEffect()
    {
        self::assertEquals(
            'e_cut_out,l_logo/fl_layer_apply,g_south,y_20',
            (string)Effect::cutOut('logo')->position(Position::south()->offsetY(20))
        );
    }

    public function testGradientFade()
    {
        self::assertEquals(
            'e_gradient_fade',
            (string)Effect::gradientFade()
        );

        self::assertEquals(
            'e_gradient_fade:symmetric',
            (string)Effect::gradientFade(GradientFade::symmetric())
        );

        self::assertEquals(
            'e_gradient_fade:symmetric:50',
            (string)Effect::gradientFade()->strength(50)->type(GradientFade::symmetric())
        );

        self::assertEquals(
            'e_gradient_fade:symmetric:50,x_0.3,y_0.15',
            (string)Effect::gradientFade()
                          ->horizontalStartPoint(0.3)
                          ->verticalStartPoint(0.15)
                          ->type(GradientFade::symmetric())
                          ->strength(50)
        );
    }

    public function testDistortArc()
    {
        self::assertEquals(
            'e_distort:arc:17',
            (string)Reshape::distortArc(17)
        );

        self::assertEquals(
            'e_distort:arc:-17',
            (string)Reshape::distortArc(-17)
        );

        $this->expectException(OutOfRangeException::class);

        Reshape::distortArc(361);
    }

    public function testDistort()
    {
        self::assertEquals(
            'e_distort:1:1:1:99:99:99:99:1',
            (string)Reshape::distort(
                new PointValue(1, 1),
                new PointValue(1, 99),
                new PointValue(99, 99),
                new PointValue(99, 1)
            )
        );

        self::assertEquals(
            'e_distort:1:1:1:99:99:99:99:1',
            (string)Reshape::distort([1, 1, 1, 99, 99, 99, 99, 1])
        );

        self::assertEquals(
            'e_distort:1:1:1:99:99:99:99:1',
            (string)Reshape::distort(1, 1, 1, 99, 99, 99, 99, 1)
        );
    }

    public function testShear()
    {
        self::assertEquals(
            'e_shear:30:0',
            (string)Reshape::shear(30, 0)
        );

        self::assertEquals(
            'e_shear:30:60',
            (string)Reshape::shear(30, 60)
        );

        self::assertEquals(
            'e_shear:30:60',
            (string)Reshape::shear(30)->skewY(60)
        );

        self::assertEquals(
            'e_shear:30:60',
            (string)Reshape::shear()->skewX(30)->skewY(60)
        );
    }

    /**
     * Data provider for testArtisticFilter()
     *
     * @return array
     */
    public function artisticFilterDataProvider()
    {
        return [
            ['al_dente', 'alDente'],
            ['athena', 'athena'],
            ['audrey', 'audrey'],
            ['aurora', 'aurora'],
            ['daguerre', 'daguerre'],
            ['eucalyptus', 'eucalyptus'],
            ['fes', 'fes'],
            ['frost', 'frost'],
            ['hairspray', 'hairspray'],
            ['hokusai', 'hokusai'],
            ['incognito', 'incognito'],
            ['linen', 'linen'],
            ['peacock', 'peacock'],
            ['primavera', 'primavera'],
            ['quartz', 'quartz'],
            ['red_rock', 'redRock'],
            ['refresh', 'refresh'],
            ['sizzle', 'sizzle'],
            ['sonnet', 'sonnet'],
            ['ukulele', 'ukulele'],
            ['zorro', 'zorro'],
        ];
    }

    /**
     * Should create artistic filters.
     *
     * @dataProvider artisticFilterDataProvider
     *
     * @param string $filter
     * @param string $method
     */
    public function testArtisticFilter($filter, $method)
    {
        self::assertEquals(
            'e_art:' . $filter,
            (string)Effect::artisticFilter(ArtisticFilter::{$method}())
        );
    }

    public function testArtisticFilterCustom()
    {
        self::assertEquals(
            'e_art:custom',
            (string)Effect::artisticFilter('custom')
        );
    }

    public function testCartoonify()
    {
        self::assertEquals(
            'e_cartoonify:17:19',
            (string)new Cartoonify(17, 19)
        );

        self::assertEquals(
            'e_cartoonify:17:19',
            (string)Effect::cartoonify(17, 19)
        );

        self::assertEquals(
            'e_cartoonify:17:19',
            (string)Effect::cartoonify()->lineStrength(17)->colorReductionLevel(19)
        );

        self::assertEquals(
            'e_cartoonify:17:bw',
            (string)Effect::cartoonify()->lineStrength(17)->blackWhite()
        );
    }

    public function testMiscEffects()
    {
        self::assertEquals(
            'e_adv_redeye',
            (string)Effect::advancedRedEye()
        );

        self::assertEquals(
            'e_redeye',
            (string)Effect::redEye()
        );

        self::assertEquals(
            'e_oil_paint:17',
            (string)Effect::oilPaint()->strength(17)
        );

        self::assertEquals(
            'l_lighthouse/e_style_transfer,fl_layer_apply',
            (string)Effect::styleTransfer('lighthouse')
        );

        self::assertEquals(
            'l_lighthouse/e_style_transfer:40,fl_layer_apply',
            (string)Effect::styleTransfer('lighthouse', 40)
        );

        self::assertEquals(
            'l_lighthouse/e_style_transfer:40,fl_layer_apply',
            (string)Effect::styleTransfer('lighthouse', 40, false)
        );

        self::assertEquals(
            'l_lighthouse/e_style_transfer:preserve_color:40,fl_layer_apply',
            (string)Effect::styleTransfer('lighthouse')->preserveColor()->strength(40)
        );

        // Test instantiating an Effect and overwriting values passed to constructor
        $effect = new StyleTransfer('source1', 40);
        $effect->preserveColor(true);
        $effect->strength(20);
        $effect->source('source2');

        self::assertEquals(
            'l_source2/e_style_transfer:preserve_color:20,fl_layer_apply',
            (string)$effect
        );

        self::assertEquals(
            'e_vectorize',
            (string)Effect::vectorize()
        );

        self::assertEquals(
            'e_vectorize:colors:2:corners:25:despeckle:50:detail:0.5:paths:100',
            (string)Effect::vectorize()->numOfColors(2)->detailsLevel(0.5)->despeckleLevel(50)->cornersLevel(25)
                          ->paths(100)
        );
    }

    public function testPlaybackEffects()
    {
        self::assertEquals(
            'e_loop',
            (string)Effect::loop()
        );

        self::assertEquals(
            'e_loop:17',
            (string)Effect::loop(17)
        );

        self::assertEquals(
            'e_loop:17',
            (string)Effect::loop()->additionalIterations(17)
        );

        self::assertEquals(
            'e_accelerate:100',
            (string)Effect::accelerate()->rate(100)
        );

        self::assertEquals(
            'e_boomerang',
            (string)Effect::boomerang()
        );

        self::assertEquals(
            'e_reverse',
            (string)Effect::reverse()
        );
    }

    public function testLightroomFilters()
    {
        self::assertEquals(
            'e_lightroom:blacks_50:exposure_3.5:generic_17',
            (string)Effect::lightroom()->blacks(50)->exposure(3.5)->genericFilter("generic", 17)
        );

        self::assertEquals(
            'e_lightroom:whitebalance_fluorescent',
            (string)Effect::lightroom()->whiteBalance(WhiteBalance::FLUORESCENT)
        );

        self::assertEquals(
            'e_lightroom:xmp:my_presets:warm_shadow.xmp',
            (string)Effect::lightroom()->xmp('my_presets/warm_shadow.xmp')
        );

        self::assertEquals(
            'e_lightroom:xmp:authenticated:my_presets:warm_shadow.xmp',
            (string)Effect::lightroom()->xmp(Xmp::source('my_presets/warm_shadow.xmp')->authenticated())
        );
    }

    public function testGenericEffects()
    {
        self::assertEquals(
            'co_orange,e_outline:outer:15:200',
            (string)Effect::generic('outline:outer', 15, 200)->addQualifier(Qualifier::generic('co', 'orange'))
        );

        self::assertEquals(
            'e_outline:outer:15:200',
            (string)Effect::fromParams(['outline', 'outer', 15, 200])
        );

        self::assertEquals(
            'e_sepia',
            (string)Effect::fromParams('sepia')
        );
    }

    public function testThemeEffects()
    {
        self::assertEquals(
            'e_theme:color_black:photosensitivity_50',
            (string)Effect::theme(Color::black())->photoSensitivity(50)
        );

        self::assertEquals(
            'e_theme:color_black:photosensitivity_50',
            (string)Effect::theme(Color::black(), 50)
        );

        self::assertEquals(
            'e_theme:color_ff9900',
            (string)Effect::theme(Color::rgb('#ff9900'))
        );

        self::assertEquals(
            'e_theme:color_ff9900',
            (string)Effect::theme(Color::rgb('ff9900'))
        );
    }
}
