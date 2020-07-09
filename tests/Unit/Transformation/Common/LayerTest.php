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

use Cloudinary\Test\Unit\Asset\AssetTestCase;
use Cloudinary\Transformation\Adjust;
use Cloudinary\Transformation\Argument\Color;
use Cloudinary\Transformation\Argument\ColorValue;
use Cloudinary\Transformation\Argument\Text\FontAntialiasing;
use Cloudinary\Transformation\Argument\Text\FontFamily;
use Cloudinary\Transformation\Argument\Text\FontHinting;
use Cloudinary\Transformation\Argument\Text\FontWeight;
use Cloudinary\Transformation\Argument\Text\TextStyle;
use Cloudinary\Transformation\Crop;
use Cloudinary\Transformation\FocalGravity;
use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\ImageLayer;
use Cloudinary\Transformation\LutLayer;
use Cloudinary\Transformation\Parameter\VideoRange\VideoRange;
use Cloudinary\Transformation\SubtitlesLayer;
use Cloudinary\Transformation\TextLayer;
use Cloudinary\Transformation\TextLayerParam;
use Cloudinary\Transformation\Transformation;
use Cloudinary\Transformation\VideoLayer;
use Cloudinary\Transformation\VideoTransformation;

/**
 * Class LayerTest
 */
final class LayerTest extends AssetTestCase
{
    public function testImageLayer()
    {
        $t = new Transformation();

        $t->resize(Crop::thumbnail(100, 200, Gravity::auto(FocalGravity::ADVANCED_EYES)))
          ->adjust(Adjust::hue(99))
          ->adjust(Adjust::replaceColor(Color::PINK, 50, Color::CYAN));

        $t_expected = 'c_thumb,g_auto:adv_eyes,h_200,w_100/e_hue:99/e_replace_color:pink:50:cyan';

        $publicId = 'some/resource/in/path/test';

        $this->assertEquals(
            "l_some:resource:in:path:test/$t_expected/fl_layer_apply",
            (string)(new ImageLayer($publicId))->transformation($t)
        );
    }

    public function testImageLayerWithPosition()
    {
        $t = new Transformation();

        $t->resize(Crop::thumbnail(100, 200, Gravity::auto(FocalGravity::ADVANCED_EYES)))
          ->adjust(Adjust::hue(99))
          ->adjust(Adjust::replaceColor(ColorValue::pink(), 50, ColorValue::cyan()));

        $t_expected = 'c_thumb,g_auto:adv_eyes,h_200,w_100/e_hue:99/e_replace_color:pink:50:cyan';

        $publicId = 'some/resource/in/path/test';

        $this->assertEquals(
            "l_some:resource:in:path:test/$t_expected/fl_layer_apply",
            (string)(new ImageLayer($publicId))->transformation($t)
        );
    }

    public function testTextLayer()
    {
        $t = new Transformation();

        $t->adjust(Adjust::hue(99))
          ->adjust(Adjust::replaceColor(Color::PINK, 50, Color::CYAN));

        $tExpected = 'e_hue:99/e_replace_color:pink:50:cyan';

        $text         = 'Some long text, bla-bla';
        $textExpected = 'Some%20long%20text%252C%20bla-bla';

        $style         = (new TextStyle('Arial', 14))->fontAntialiasing(FontAntialiasing::FAST)->letterSpacing(17);
        $styleExpected = 'Arial_14_demibold_antialias_fast_letter_spacing_17';
        $flagsExpected = 'fl_text_no_trim/fl_text_disallow_overflow';
        $this->assertEquals(
            "b_violet,co_orchid,l_text:$styleExpected:$textExpected/$tExpected/$flagsExpected/fl_layer_apply",
            (string)(new TextLayer($text, $style))
                ->fontWeight(FontWeight::DEMIBOLD)
                ->color(ColorValue::orchid())
                ->background(ColorValue::violet())
                ->transformation($t)
                ->noTrim()
                ->disallowOverflow()
        );
    }

    public function testSubtitlesLayer()
    {
        $this->assertEquals(
            'b_green,co_red,l_subtitles:Arial_14:sample_sub_en.srt/fl_layer_apply',
            (string)(new SubtitlesLayer('sample_sub_en.srt'))
                ->color(Color::RED)
                ->background(Color::GREEN)
                ->fontFamily(FontFamily::ARIAL)
                ->fontSize(14)
        );
    }

    public function testVideoLayer()
    {
        $t = new VideoTransformation();

        $t->trim(VideoRange::range(0, 10)->duration(10));

        $tExpected = 'so_0,eo_10,du_10';

        $this->assertEquals(
            "l_video:dog/$tExpected/fl_layer_apply",
            (string)(new VideoLayer('dog'))->transformation($t)
        );
    }

    public function testLutLayer()
    {
        $this->assertEquals(
            'l_lut:iwltbap_aspen.3dl/fl_layer_apply',
            (string)new LutLayer('iwltbap_aspen.3dl')
        );
    }

    public function testTextLayerParameter()
    {
        $text         = 'Some long text, bla-bla';
        $textExpected = 'Some%20long%20text%252C%20bla-bla';

        $style         = (new TextStyle(FontFamily::ARIAL, 14))
            ->fontAntialiasing(FontAntialiasing::FAST)
            ->fontHinting(FontHinting::SLIGHT)
            ->letterSpacing(17)
            ->fontWeight(FontWeight::DEMIBOLD);
        $styleExpected = 'Arial_14_demibold_antialias_fast_hinting_slight_letter_spacing_17';

        $this->assertEquals(
            "l_text:$styleExpected:$textExpected",
            (string)new TextLayerParam($text, $style)
        );
    }

    public function testLayerFlags()
    {
        $this->assertEquals(
            'l_logo/fl_region_relative/fl_no_overflow/fl_tiled/fl_layer_apply',
            (string)(new ImageLayer('logo'))->regionRelative()->noOverflow()->tiled()
        );
    }
}
