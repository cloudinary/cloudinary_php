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

use Cloudinary\StringUtils;
use Cloudinary\Transformation\Adjust;
use Cloudinary\Transformation\Argument\Color;
use Cloudinary\Transformation\Argument\ColorValue;
use Cloudinary\Transformation\Argument\GradientDirection;
use Cloudinary\Transformation\Argument\Text\FontFamily;
use Cloudinary\Transformation\Argument\Text\FontStyle;
use Cloudinary\Transformation\Argument\Text\FontWeight;
use Cloudinary\Transformation\Argument\Text\Stroke;
use Cloudinary\Transformation\Argument\Text\TextDecoration;
use Cloudinary\Transformation\AudioCodec;
use Cloudinary\Transformation\AudioFrequency;
use Cloudinary\Transformation\Background;
use Cloudinary\Transformation\Border;
use Cloudinary\Transformation\ChromaSubSampling;
use Cloudinary\Transformation\Codec\VideoCodecLevel;
use Cloudinary\Transformation\Codec\VideoCodecProfile;
use Cloudinary\Transformation\ColorSpace;
use Cloudinary\Transformation\Compass;
use Cloudinary\Transformation\CompassGravity;
use Cloudinary\Transformation\Conditional;
use Cloudinary\Transformation\Crop;
use Cloudinary\Transformation\CustomFunction;
use Cloudinary\Transformation\Effect;
use Cloudinary\Transformation\Expression\PVar;
use Cloudinary\Transformation\Extract;
use Cloudinary\Transformation\Fill;
use Cloudinary\Transformation\FocalGravity;
use Cloudinary\Transformation\FocusOn;
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\ImageSource;
use Cloudinary\Transformation\LayerFlag;
use Cloudinary\Transformation\ObjectGravity;
use Cloudinary\Transformation\OutlineMode;
use Cloudinary\Transformation\Pad;
use Cloudinary\Transformation\Position;
use Cloudinary\Transformation\PsdTools;
use Cloudinary\Transformation\Qualifier;
use Cloudinary\Transformation\Quality;
use Cloudinary\Transformation\Reshape;
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\RoundCorners;
use Cloudinary\Transformation\Scale;
use Cloudinary\Transformation\Source;
use Cloudinary\Transformation\TextSource;
use Cloudinary\Transformation\TextStyle;
use Cloudinary\Transformation\Timeline;
use Cloudinary\Transformation\Transformation;
use Cloudinary\Transformation\VideoCodec;
use Cloudinary\Transformation\VideoSource;
use Cloudinary\Transformation\VideoTransformation;
use PHPUnit\Framework\TestCase;

/**
 * Class UsageTest
 */
final class UsageTest extends TestCase
{
    public function testScale()
    {
        self::assertEquals(
            'c_scale,w_800',
            (string)(new Transformation())->resize(Scale::scale(800))
        );

        self::assertEquals(
            'c_scale,h_800',
            (string)(new Transformation())->resize(Scale::scale()->height(800))
        );

        self::assertEquals(
            'c_scale,h_800,w_800',
            (string)(new Transformation())->resize(Scale::scale(800, 800))
        );
        // OR
        self::assertEquals(
            'c_scale,h_800,w_800',
            (string)(new Transformation())->resize(Scale::scale(800)->height(800))
        );

        self::assertEquals(
            'ar_2.111,c_scale,h_800',
            (string)(new Transformation())->resize(Scale::scale()->height(800)->aspectRatio(2.111))
        );

        self::assertEquals(
            'ar_19:9,c_scale,h_800',
            (string)(new Transformation())->resize(Scale::scale()->height(800)->aspectRatio('19:9'))
        );

        self::assertEquals(
            'ar_19:9,c_scale,h_800',
            (string)(new Transformation())->resize(Scale::scale()->height(800)->aspectRatio(19, 9))
        );

        // Will throw an exception (too many params)
        //(string)(new Transformation())->resize(Scale::scale()->height(800)->aspectRatio(19, 9, 9));
    }

    public function testRoundCorners()
    {
        self::assertEquals(
            'r_70',
            (string)(new Transformation())->roundCorners(70)
        );

        self::assertEquals(
            'r_max',
            (string)(new Transformation())->roundCorners(RoundCorners::max())
        );
    }

    public function testCrop()
    {
        self::assertEquals(
            'c_thumb,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80))
        );

        self::assertEquals(
            'c_thumb,g_auto,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, Gravity::auto()))
        );

        self::assertEquals(
            'c_thumb,h_80,w_80,x_10,y_10',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80)->position(10, 10))
        );

        self::assertEquals(
            'c_thumb,g_auto:body:clown,h_80,w_80',
            (string)(new Transformation())->resize(
                Crop::thumbnail(80, 80)
                    ->gravity(Gravity::auto(FocalGravity::BODY)->add('clown'))
            )
        );

        self::assertEquals(
            'c_thumb,g_north,h_80,w_80',
            (string)(new Transformation())
                ->resize(
                    Crop::thumbnail(80, 80)
                        ->gravity(CompassGravity::north())
                )
        );

        self::assertEquals(
            'c_thumb,g_face:center,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, Gravity::face(Compass::CENTER)))
        );

        self::assertEquals(
            'c_thumb,g_auto,h_80,w_80,z_0.7',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, Gravity::auto())->zoom(0.7))
        );

        self::assertEquals(
            'c_thumb,g_up,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80)->gravity('up')) // not real gravity
        );
    }

    public function testObjectGravity()
    {
        self::assertEquals(
            'c_thumb,g_auto:bowl:cup,h_80,w_80',
            (string)(new Transformation())->resize(
                Crop::thumbnail(80, 80)->gravity(Gravity::auto(ObjectGravity::BOWL)->add('cup'))
            )
        );

        // empty object gravity omits g_
        self::assertEquals(
            'c_thumb,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80)->gravity(Gravity::object()))
        );

        self::assertEquals(
            'c_thumb,g_cat,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, Gravity::object(ObjectGravity::CAT)))
        );

        self::assertEquals(
            'c_thumb,g_accessory,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, FocusOn::accessory()))
        );

        self::assertEquals(
            'c_thumb,g_animal,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, FocusOn::animal()))
        );

        self::assertEquals(
            'c_thumb,g_appliance,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, FocusOn::appliance()))
        );

        self::assertEquals(
            'c_thumb,g_electronic,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, FocusOn::electronic()))
        );

        self::assertEquals(
            'c_thumb,g_food,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, FocusOn::food()))
        );

        self::assertEquals(
            'c_thumb,g_furniture,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, FocusOn::furniture()))
        );

        self::assertEquals(
            'c_thumb,g_indoor,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, FocusOn::indoor()))
        );

        self::assertEquals(
            'c_thumb,g_kitchen,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, FocusOn::kitchen()))
        );

        self::assertEquals(
            'c_thumb,g_outdoor,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, FocusOn::outdoor()))
        );

        self::assertEquals(
            'c_thumb,g_person,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, FocusOn::person()))
        );

        self::assertEquals(
            'c_thumb,g_vehicle,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, FocusOn::vehicle()))
        );

        self::assertEquals(
            'c_thumb,g_person:kitchen,h_80,w_80',
            (string)(new Transformation())->resize(
                Crop::thumbnail(80, 80, Gravity::focusOn(FocusOn::person(), FocusOn::kitchen()))
            )
        );

        self::assertEquals(
            'c_crop,g_person:kitchen:cup,h_80,w_80',
            (string)(new Transformation())->resize(
                Crop::crop(80, 80, Gravity::focusOn(FocusOn::person(), FocusOn::kitchen(), FocusOn::cup()))
            )
        );
    }

    public function testCustomFunction()
    {
        $wasmSource          = 'blur.wasm';
        $remoteSource        = 'https://df34ra4a.execute-api.us-west-2.amazonaws.com/default/cloudinaryFn';
        $encodedRemoteSource = StringUtils::base64UrlEncode($remoteSource);

        self::assertEquals(
            "fn_wasm:$wasmSource",
            (string)(new Transformation())->customFunction(CustomFunction::wasm($wasmSource))
        );

        self::assertEquals(
            "fn_remote:$encodedRemoteSource",
            (string)(new Transformation())->customFunction(CustomFunction::remote($remoteSource))
        );

        self::assertEquals(
            "fn_pre:remote:$encodedRemoteSource",
            (string)(new Transformation())->customFunction(CustomFunction::remote($remoteSource)->preprocess())
        );
    }

    public function testPad()
    {
        self::assertEquals(
            'b_green,c_pad,h_80,w_80',
            (string)(new Transformation())->resize(Pad::pad(80, 80)->background(Color::GREEN))
        );

        self::assertEquals(
            'b_auto:border_contrast,c_pad,h_80,w_80',
            (string)(new Transformation())->resize(Pad::pad(80, 80)->background(Background::border()->contrast()))
        );

        self::assertEquals(
            'b_auto:predominant_gradient:2:diagonal_desc,c_pad,h_80,w_80',
            (string)(new Transformation())
                ->resize(
                    Pad::pad(80, 80)->background(Background::predominantGradient(2, GradientDirection::DIAGONAL_DESC))
                )
        );

        //c_thumb,h_80,w_80,g_auto:cars:cats
        // TODO: implement SUBJECT
        //new Transformation().crop(Crop.thumb(80, 80, Gravity.object([Gravity.Objects.CARS, Gravity.Objects.CATS])))
    }

    public function testMultipleTransformations()
    {
        self::assertEquals(
            'c_thumb,g_auto,h_80,w_80/c_scale,h_80,w_80',
            (string)(new Transformation())
                ->resize(Crop::thumbnail(80, 80, Gravity::auto()))
                ->resize(Scale::scale(80, 80))
        );
    }

    public function testEffects()
    {
        self::assertEquals(
            'e_kuku:11:some_param',
            (string)(new Transformation())->effect(Effect::generic('kuku', 11, 'some_param'))
        );

        self::assertEquals(
            'e_sepia',
            (string)(new Transformation())->effect(Effect::sepia())
        );

        self::assertEquals(
            'e_saturation:82',
            (string)(new Transformation())->adjust(Adjust::saturation(82))
        );

        self::assertEquals(
            'e_tint:100:green:red',
            (string)(new Transformation())->adjust(Adjust::tint(100, Color::GREEN, Color::RED))
        );

        self::assertEquals(
            'co_green,e_shadow,x_10,y_h_div_50',
            (string)(new Transformation())->effect(
                Effect::shadow()->offset(10, PVar::height()->divide()->numeric(50))->color(Color::GREEN)
            )
        );

        self::assertEquals(
            'e_replace_color:maroon:80:2b38aa',
            (string)(new Transformation())->adjust(Adjust::replaceColor(Color::MAROON, 80, '2b38aa'))
        );

        self::assertEquals(
            'e_replace_color:maroon:80:2b38aa',
            (string)(new Transformation())->adjust(Adjust::replaceColor(Color::MAROON, 80, '#2b38aa'))
        );

        self::assertEquals(
            'e_replace_color:maroon:80:2b38aa',
            (string)(new Transformation())->adjust(Adjust::replaceColor(Color::MAROON, 80, Color::rgb('2b38aa')))
        );

        self::assertEquals(
            'co_orange,e_outline:inner:15:200',
            (string)(new Transformation())
                ->effect(
                    Effect::outline()->mode(OutlineMode::inner())->width(15)->blurLevel(200)->color(Color::ORANGE)
                )
        );
    }

    public function testBorder()
    {
        self::assertEquals(
            'bo_4px_solid_hotpink,r_max',
            (string)(new Transformation())->border(
                Border::solid()->width(4)->color(Color::HOTPINK)->roundCorners(
                    RoundCorners::max()
                )
            )
        );
    }

    /**
     * Data provider for the `testFormat()` test.
     *
     * @return string[][]
     */
    public function formatDataProvider()
    {
        return [
            [Format::AUTO, 'auto'],
            [Format::GIF, 'gif'],
            [Format::PNG, 'png'],
            [Format::JPG, 'jpg'],
            [Format::BMP, 'bmp'],
            [Format::ICO, 'ico'],
            [Format::PDF, 'pdf'],
            [Format::TIFF, 'tiff'],
            [Format::EPS, 'eps'],
            [Format::JPC, 'jpc'],
            [Format::JP2, 'jp2'],
            [Format::PSD, 'psd'],
            [Format::WEBP, 'webp'],
            [Format::SVG, 'svg'],
            [Format::WDP, 'wdp'],
            [Format::DJVU, 'djvu'],
            [Format::AI, 'ai'],
            [Format::AVIF, 'avif'],
            [Format::FLIF, 'flif'],
            [Format::USDZ, 'usdz'],
            [Format::AVI, 'videoAvi'],
            [Format::MP4, 'videoMp4'],
            [Format::WEBM, 'videoWebm'],
            [Format::MOV, 'videoMov'],
            [Format::OGV, 'videoOgv'],
            [Format::WMV, 'videoWmv'],
            [Format::MPEG, 'videoMpeg'],
            [Format::FLV, 'videoFlv'],
            [Format::M3U8, 'videoM3u8'],
            [Format::TS, 'videoTs'],
            [Format::MKV, 'videoMkv'],
            [Format::MPD, 'videoMpd'],
            [Format::MP3, 'audioMp3'],
            [Format::AAC, 'audioAac'],
            [Format::M4A, 'audioM4a'],
            [Format::OGG, 'audioOgg'],
            [Format::WAV, 'audioWav'],
            [Format::AIFF, 'audioAiff'],
            [Format::FLAC, 'audioFlac'],
            [Format::AMR, 'audioAmr'],
            [Format::MIDI, 'audioMidi'],
        ];
    }

    /**
     * @dataProvider formatDataProvider
     *
     * @param string $format
     * @param string $method
     */
    public function testFormat($format, $method)
    {
        self::assertEquals(
            'f_' . $format,
            (string)(new Transformation())->format(Format::{$method}())
        );
    }

    public function testQuality()
    {
        self::assertEquals(
            'q_70',
            (string)(new Transformation())->quality(70)
        );

        self::assertEquals(
            'q_70:420',
            (string)(new Transformation())->quality(
                Quality::level(70)->chromaSubSampling(ChromaSubSampling::chroma420())
            )
        );

        self::assertEquals(
            'q_70:qmax_80',
            (string)(new Transformation())->quality(Quality::level(70)->quantization(80))
        );

        self::assertEquals(
            'q_auto',
            (string)(new Transformation())->quality(Quality::auto())
        );

        self::assertEquals(
            'fl_any_format,q_auto',
            (string)(new Transformation())->quality(Quality::auto()->anyFormat())
        );

        self::assertEquals(
            'q_auto:good',
            (string)(new Transformation())->quality(Quality::autoGood())
        );

        self::assertEquals(
            'q_auto:some_new_param',
            (string)(new Transformation())->quality(Quality::auto('some_new_param'))
        );
    }

    public function testNamedTransformation()
    {
        self::assertEquals(
            't_my_transformation',
            (string)(new Transformation())->namedTransformation('my_transformation')
        );
    }

    public function testGenericTransformation()
    {
        $genericT = 'c_crop,w_100,e_sepia/r_max/a_17';

        self::assertEquals(
            $genericT,
            (string)(new Transformation($genericT))
        );

        self::assertEquals(
            "$genericT/c_fill,h_80/$genericT",
            (string)(new Transformation($genericT))->resize(Fill::fill()->height(80))->addTransformation($genericT)
        );
    }

    public function testLayers()
    {
        self::assertEquals(
            'l_blabla/fl_layer_apply',
            (string)(new Transformation())->overlay('blabla')
        );

        self::assertEquals(
            'u_blabla/fl_layer_apply',
            (string)(new Transformation())->underlay(ImageSource::image('blabla'))
        );

        self::assertEquals(
            'l_blabla/c_scale,h_80,w_80/fl_layer_apply',
            (string)(new Transformation())->overlay(ImageSource::image('blabla')->resize(Scale::scale(80, 80)))
        );

        self::assertEquals(
            'l_blabla/c_fill,g_auto,h_80,w_80/fl_layer_apply,x_10,y_10',
            (string)(new Transformation())->overlay(
                (new ImageSource('blabla'))->resize(Fill::fill(80, 80, Gravity::auto())),
                Position::absolute(10, 10)
            )
        );

        self::assertEquals(
            'l_text:Arial_50:my_text/fl_layer_apply',
            (string)(new Transformation())->overlay(
                new TextSource('my_text', new TextStyle(FontFamily::ARIAL, 50))
            )
        );

        self::assertEquals(
            'l_text:Verdana_75_bold_italic_underline_letter_spacing_14:Flowers/fl_layer_apply',
            (string)(new Transformation())->overlay(
                ImageSource::text(
                    'Flowers',
                    (new TextStyle())
                        ->fontFamily(FontFamily::VERDANA)
                        ->fontSize(75)
                        ->fontWeight(FontWeight::BOLD)
                        ->fontStyle(FontStyle::ITALIC)
                        ->textDecoration(TextDecoration::UNDERLINE)
                        ->letterSpacing(14)
                )
            )
        );

        self::assertEquals(
            'b_red,bo_2px_solid_white,co_green,l_text:Impact_150_stroke:Your%20Logo%20Here/e_distort:arc:-120' .
            '/fl_layer_apply,g_south,y_840',
            (string)(new Transformation())->overlay(
                ImageSource::text(
                    'Your Logo Here',
                    new TextStyle(FontFamily::IMPACT, 150)
                )
                           ->stroke(Stroke::solid(2, Color::WHITE))
                           ->textColor(Color::GREEN)
                           ->backgroundColor(Color::RED)
                           ->reshape(Reshape::distortArc(-120)),
                Position::south(null, 840)
            )
        );

        self::assertEquals(
            'l_lut:iwltbap_aspen.3dl/fl_layer_apply',
            (string)(new Transformation())->adjust(Adjust::by3dLut('iwltbap_aspen.3dl'))
        );
    }

    public function testPage()
    {
        self::assertEquals(
            'pg_2',
            (string)(new Transformation())->extract(2)
        );

        self::assertEquals(
            'pg_3;5-7;9-',
            (string)(new Transformation())->extract(Extract::getPage()->byNumber(3)->byRange(5, 7)->byRange(9))
        );

        self::assertEquals(
            'pg_3;5-7;9-',
            (string)(new Transformation())->extract(Extract::getPage()->byNumber(3)->byNumber('5-7')->byRange(9))
        );

        self::assertEquals(
            'pg_name:record_cover;Shadow',
            (string)(new Transformation())->psdTools(PsdTools::getLayer()->byNames('record_cover', 'Shadow'))
        );

        self::assertEquals(
            'pg_name:record_cover;Shadow;record_cover2;Shadow2',
            (string)(new Transformation())->psdTools(PsdTools::getLayer()->byNames('record_cover', 'Shadow')
                                                             ->byName('record_cover2')->byName('Shadow2'))
        );
    }

    public function testExpressions()
    {
        self::assertEquals(
            'if_w_gt_1000/l_text:Arial_50:this%20is%20big/c_fill,h_80,w_80/fl_layer_apply/if_else/e_grayscale/if_end',
            (string)(new Transformation())
                ->conditional(
                    Conditional::ifCondition(
                        PVar::width()->greaterThan()->numeric(1000),
                        (new Transformation())
                            ->overlay(
                                Source::text('this is big', new TextStyle(FontFamily::ARIAL, 50))
                                      ->resize(Resize::fill(80, 80))
                            )
                    )->otherwise(Effect::grayscale())
                )
        );
    }

    public function testCustomParameter()
    {
        self::assertEquals(
            'w_500',
            (string)(new Transformation())->addGenericQualifier('w', 500)
        );
    }

    public function testParametersBuilder()
    {
        self::assertEquals(
            'w_100/h_200/ar_19:9/x_300/y_400/co_aquamarine/cn_cv/d_public_id/dl_1/dn_1.5/l_id/fl_layer_apply/' .
            'u_id/fl_layer_apply/pg_2/bo_rgb:ff00ff/dpr_2.5/so_2.51/eo_3.01/du_5/cs_srgb/z_1.1/ac_aac/af_8000',
            (string)(new Transformation())
                ->addAction(Qualifier::width(100))
                ->addAction(Qualifier::height(200))
                ->addAction(Qualifier::aspectRatio(19, 9))
                ->addAction(Qualifier::x(300))
                ->addAction(Qualifier::y(400))
                ->addAction(Qualifier::color(ColorValue::aquamarine()))
                ->addAction(Qualifier::generic('cn', 'cv'))
                ->addAction(Qualifier::defaultImage('public_id'))
                ->addAction(Qualifier::delay(1))
                ->addAction(Qualifier::density(1.5))
                ->addAction(Qualifier::overlay('id'))
                ->addAction(Qualifier::underlay('id'))
                ->addAction(Qualifier::page(2))
                ->addAction(Qualifier::border('#ff00ff'))
                ->addAction(Qualifier::dpr(2.5))
                ->addAction(Qualifier::startOffset(2.51))
                ->addAction(Qualifier::endOffset(3.01))
                ->addAction(Qualifier::duration(5))
                ->addAction(Qualifier::colorSpace(ColorSpace::SRGB))
                ->addAction(Qualifier::zoom(1.1))
                ->addAction(Qualifier::audioCodec(AudioCodec::AAC))
                ->addAction(Qualifier::audioFrequency(AudioFrequency::FREQ8000))
        );
    }

    public function testVideo()
    {
        self::assertEquals(
            'eo_10,so_6.5',
            (string)(new VideoTransformation())->trim(Timeline::position(6.5, 10))
        );

        self::assertEquals(
            'du_30p,so_10p',
            (string)(new VideoTransformation())->trim(Timeline::position('10p')->duration('30p'))
        );

        self::assertEquals(
            'c_fill,h_200,w_300/fl_splice,l_video:dog/eo_5,so_0/c_fill,h_200,w_300/fl_layer_apply',
            (string)(new VideoTransformation())
                ->resize(Fill::fill(300, 200))
                ->overlay(
                    VideoSource::video('dog')
                               ->trim(Timeline::position(0, 5))
                               ->resize(Fill::fill(300, 200))
                               ->setFlag(LayerFlag::splice())
                )
        );

        self::assertEquals(
            'vc_h264:baseline:3.1',
            (string)(new VideoTransformation())
                ->transcode(VideoCodec::h264(VideoCodecProfile::baseline(), VideoCodecLevel::vcl31()))
        );

        self::assertEquals(
            'af_22050',
            (string)(new VideoTransformation())->transcode(AudioFrequency::freq22050())
        );

        self::assertEquals(
            'af_iaf',
            (string)(new VideoTransformation())->transcode(AudioFrequency::iaf())
        );

        self::assertEquals(
            'ac_none',
            (string)(new VideoTransformation())->transcode(AudioCodec::none())
        );

        self::assertEquals(
            'l_subtitles:sample_sub_en.srt/fl_layer_apply',
            (string)(new VideoTransformation())->addSubtitles('sample_sub_en.srt')
        );
    }
}
