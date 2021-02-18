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
use Cloudinary\Transformation\Argument\Text\FontAntialias;
use Cloudinary\Transformation\Argument\Text\FontFamily;
use Cloudinary\Transformation\Argument\Text\FontHinting;
use Cloudinary\Transformation\Argument\Text\FontWeight;
use Cloudinary\Transformation\BlendMode;
use Cloudinary\Transformation\Crop;
use Cloudinary\Transformation\FocalGravity;
use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\ImageSource;
use Cloudinary\Transformation\LutLayer;
use Cloudinary\Transformation\MediaOverlay;
use Cloudinary\Transformation\Position;
use Cloudinary\Transformation\SubtitlesSource;
use Cloudinary\Transformation\TextSource;
use Cloudinary\Transformation\TextSourceQualifier;
use Cloudinary\Transformation\TextStyle;
use Cloudinary\Transformation\Timeline;
use Cloudinary\Transformation\Transformation;
use Cloudinary\Transformation\VideoSource;
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

        $publicId = 'some/asset/in/path/test';

        self::assertEquals(
            "l_some:asset:in:path:test/$t_expected/fl_layer_apply",
            (string)(new ImageSource($publicId))->transformation($t)
        );
    }

    public function testImageLayerWithPosition()
    {
        $t = new Transformation();

        $t->resize(Crop::thumbnail(100, 200, Gravity::auto(FocalGravity::ADVANCED_EYES)))
          ->adjust(Adjust::hue(99))
          ->adjust(Adjust::replaceColor(ColorValue::pink(), 50, ColorValue::cyan()));

        $t_expected = 'c_thumb,g_auto:adv_eyes,h_200,w_100/e_hue:99/e_replace_color:pink:50:cyan';

        $publicId = 'some/asset/in/path/test';

        self::assertEquals(
            "l_some:asset:in:path:test/$t_expected/fl_layer_apply",
            (string)(new ImageSource($publicId))->transformation($t)
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

        $style         = (new TextStyle('Arial', 14))->fontAntialias(FontAntialias::FAST)->letterSpacing(17);
        $styleExpected = 'Arial_14_demibold_antialias_fast_letter_spacing_17';
        $flagsExpected = 'fl_text_no_trim/fl_text_disallow_overflow';
        self::assertEquals(
            "b_violet,co_orchid,l_text:$styleExpected:$textExpected/$tExpected/$flagsExpected/fl_layer_apply",
            (string)(new TextSource($text, $style))
                ->fontWeight(FontWeight::DEMIBOLD)
                ->textColor(ColorValue::orchid())
                ->backgroundColor(Color::violet())
                ->transformation($t)
                ->noTrim()
                ->disallowOverflow()
        );
    }

    public function testSubtitlesLayer()
    {
        self::assertEquals(
            'b_green,co_red,l_subtitles:Arial_14:sample_sub_en.srt/fl_layer_apply',
            (string)(new SubtitlesSource('sample_sub_en.srt'))
                ->textColor(Color::RED)
                ->backgroundColor(Color::GREEN)
                ->fontFamily(FontFamily::ARIAL)
                ->fontSize(14)
        );
    }

    public function testVideoLayer()
    {
        $t = new VideoTransformation();

        $t->trim(Timeline::position(0, 10)->duration(10));

        $tExpected = 'du_10,eo_10,so_0';

        self::assertEquals(
            "l_video:dog/$tExpected/fl_layer_apply",
            (string)(new VideoSource('dog'))->transformation($t)
        );
    }

    public function testLutLayer()
    {
        self::assertEquals(
            'l_lut:iwltbap_aspen.3dl/fl_layer_apply',
            (string)new LutLayer('iwltbap_aspen.3dl')
        );
    }

    public function testMediaOverlay()
    {
        self::assertStrEquals(
            'l_media/e_mask,fl_layer_apply,g_south,so_5',
            (new MediaOverlay('media'))->position(Position::south())->blendMode(BlendMode::mask())->timeline(
                Timeline::position()->startOffset(5)
            )
        );
    }

    public function testTextLayerParameter()
    {
        $text         = 'Some long text, bla-bla';
        $textExpected = 'Some%20long%20text%252C%20bla-bla';

        $style         = (new TextStyle(FontFamily::ARIAL, 14))
            ->fontAntialias(FontAntialias::FAST)
            ->fontHinting(FontHinting::SLIGHT)
            ->letterSpacing(17)
            ->fontWeight(FontWeight::DEMIBOLD);
        $styleExpected = 'Arial_14_demibold_antialias_fast_hinting_slight_letter_spacing_17';

        self::assertEquals(
            "l_text:$styleExpected:$textExpected",
            (string)new TextSourceQualifier($text, $style)
        );
    }
}
