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
use Cloudinary\Transformation\Argument\Gradient;
use Cloudinary\Transformation\Argument\GradientDirection;
use Cloudinary\Transformation\Argument\Text\FontFamily;
use Cloudinary\Transformation\Argument\Text\FontStyle;
use Cloudinary\Transformation\Argument\Text\FontWeight;
use Cloudinary\Transformation\Argument\Text\TextDecoration;
use Cloudinary\Transformation\Argument\Text\TextStyle;
use Cloudinary\Transformation\AudioCodec;
use Cloudinary\Transformation\AudioFrequency;
use Cloudinary\Transformation\AutoBackground;
use Cloudinary\Transformation\Border;
use Cloudinary\Transformation\Chroma;
use Cloudinary\Transformation\Codec\VideoCodecLevel;
use Cloudinary\Transformation\Codec\VideoCodecProfile;
use Cloudinary\Transformation\CompassGravity;
use Cloudinary\Transformation\Crop;
use Cloudinary\Transformation\CustomFunction;
use Cloudinary\Transformation\Effect;
use Cloudinary\Transformation\Expression\PVar;
use Cloudinary\Transformation\Fill;
use Cloudinary\Transformation\FocalGravity;
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\ImageLayer;
use Cloudinary\Transformation\LayerFlag;
use Cloudinary\Transformation\ObjectGravity;
use Cloudinary\Transformation\Outline;
use Cloudinary\Transformation\Pad;
use Cloudinary\Transformation\Page;
use Cloudinary\Transformation\Parameter;
use Cloudinary\Transformation\Parameter\VideoRange\VideoRange;
use Cloudinary\Transformation\Position;
use Cloudinary\Transformation\PSDLayer;
use Cloudinary\Transformation\Quality;
use Cloudinary\Transformation\Reshape;
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\RoundCorners;
use Cloudinary\Transformation\Scale;
use Cloudinary\Transformation\Source;
use Cloudinary\Transformation\TextLayer;
use Cloudinary\Transformation\Transformation;
use Cloudinary\Transformation\VideoCodec;
use Cloudinary\Transformation\VideoLayer;
use Cloudinary\Transformation\VideoTransformation;
use PHPUnit\Framework\TestCase;

/**
 * Class UsageTest
 */
final class UsageTest extends TestCase
{
    public function testScale()
    {
        $this->assertEquals(
            'c_scale,w_800',
            (string)(new Transformation())->resize(Scale::scale(800))
        );

        $this->assertEquals(
            'c_scale,h_800',
            (string)(new Transformation())->resize(Scale::scale()->height(800))
        );

        $this->assertEquals(
            'c_scale,h_800,w_800',
            (string)(new Transformation())->resize(Scale::scale(800, 800))
        );
        // OR
        $this->assertEquals(
            'c_scale,h_800,w_800',
            (string)(new Transformation())->resize(Scale::scale(800)->height(800))
        );

        $this->assertEquals(
            'ar_2.111,c_scale,h_800',
            (string)(new Transformation())->resize(Scale::scale()->height(800)->aspectRatio(2.111))
        );

        $this->assertEquals(
            'ar_19:9,c_scale,h_800',
            (string)(new Transformation())->resize(Scale::scale()->height(800)->aspectRatio('19:9'))
        );

        $this->assertEquals(
            'ar_19:9,c_scale,h_800',
            (string)(new Transformation())->resize(Scale::scale()->height(800)->aspectRatio(19, 9))
        );

        // Will throw an exception (too many params)
        //(string)(new Transformation())->resize(Scale::scale()->height(800)->aspectRatio(19, 9, 9));
    }

    public function testRoundCorners()
    {
        $this->assertEquals(
            'r_70',
            (string)(new Transformation())->roundCorners(70)
        );

        $this->assertEquals(
            'r_max',
            (string)(new Transformation())->roundCorners(RoundCorners::max())
        );
    }

    public function testCrop()
    {
        $this->assertEquals(
            'c_thumb,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80))
        );

        $this->assertEquals(
            'c_thumb,g_auto,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, Gravity::auto()))
        );

        $this->assertEquals(
            'c_thumb,h_80,w_80,x_10,y_10',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80)->position(10, 10))
        );

        $this->assertEquals(
            'c_thumb,g_auto:body:clown,h_80,w_80',
            (string)(new Transformation())->resize(
                Crop::thumbnail(80, 80)
                    ->gravity(Gravity::auto(FocalGravity::BODY)->add('clown'))
            )
        );

        $this->assertEquals(
            'c_thumb,g_north,h_80,w_80',
            (string)(new Transformation())
                ->resize(
                    Crop::thumbnail(80, 80)
                        ->gravity(CompassGravity::north())
                )
        );

        $this->assertEquals(
            'c_thumb,g_face:center,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, Gravity::face(CompassGravity::CENTER)))
        );

        $this->assertEquals(
            'c_thumb,g_auto,h_80,w_80,z_0.7',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, Gravity::auto())->zoom(0.7))
        );

        $this->assertEquals(
            'c_thumb,g_up,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80)->gravity('up')) // not real gravity
        );
    }

    public function testObjectGravity()
    {
        $this->assertEquals(
            'c_thumb,g_auto:bowl:cup,h_80,w_80',
            (string)(new Transformation())->resize(
                Crop::thumbnail(80, 80)->gravity(Gravity::auto(ObjectGravity::BOWL)->add('cup'))
            )
        );

        // empty object gravity omits g_
        $this->assertEquals(
            'c_thumb,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80)->gravity(Gravity::object()))
        );

        $this->assertEquals(
            'c_thumb,g_cat,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, Gravity::object(ObjectGravity::CAT)))
        );

        $this->assertEquals(
            'c_thumb,g_accessory,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, Gravity::object()->accessory()))
        );

        $this->assertEquals(
            'c_thumb,g_animal,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, Gravity::object()->animal()))
        );

        $this->assertEquals(
            'c_thumb,g_appliance,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, Gravity::object()->appliance()))
        );

        $this->assertEquals(
            'c_thumb,g_electronic,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, Gravity::object()->electronic()))
        );

        $this->assertEquals(
            'c_thumb,g_food,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, Gravity::object()->food()))
        );

        $this->assertEquals(
            'c_thumb,g_furniture,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, Gravity::object()->furniture()))
        );

        $this->assertEquals(
            'c_thumb,g_indoor,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, Gravity::object()->indoor()))
        );

        $this->assertEquals(
            'c_thumb,g_kitchen,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, Gravity::object()->kitchen()))
        );

        $this->assertEquals(
            'c_thumb,g_outdoor,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, Gravity::object()->outdoor()))
        );

        $this->assertEquals(
            'c_thumb,g_person,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, Gravity::object()->person()))
        );

        $this->assertEquals(
            'c_thumb,g_vehicle,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, Gravity::object()->vehicle()))
        );

        $this->assertEquals(
            'c_thumb,g_person:kitchen,h_80,w_80',
            (string)(new Transformation())->resize(Crop::thumbnail(80, 80, Gravity::object()->person()->kitchen()))
        );

        $this->assertEquals(
            'c_crop,g_person:kitchen:cup,h_80,w_80',
            (string)(new Transformation())->resize(Crop::crop(80, 80, Gravity::object()->person()->kitchen()->cup()))
        );

        $this->assertEquals(
            'c_thumb,g_person:kitchen:cup:falafel_100:large_50,h_80,w_80',
            (string)(new Transformation())->resize(
                Crop::thumbnail(
                    80,
                    80,
                    Gravity::object()->person()->kitchen()->cup()->large(50)->priority('falafel', 100)
                )
            )
        );
    }

    public function testCustomFunction()
    {
        $wasmSource          = 'blur.wasm';
        $remoteSource        = 'https://df34ra4a.execute-api.us-west-2.amazonaws.com/default/cloudinaryFn';
        $encodedRemoteSource = StringUtils::base64UrlEncode($remoteSource);

        $this->assertEquals(
            "fn_wasm:$wasmSource",
            (string)(new Transformation())->customFunction(CustomFunction::wasm($wasmSource))
        );

        $this->assertEquals(
            "fn_remote:$encodedRemoteSource",
            (string)(new Transformation())->customFunction(CustomFunction::remote($remoteSource))
        );

        $this->assertEquals(
            "fn_pre:remote:$encodedRemoteSource",
            (string)(new Transformation())->customFunction(CustomFunction::preprocessRemote($remoteSource))
        );
    }

    public function testPad()
    {
        $this->assertEquals(
            'b_green,c_pad,h_80,w_80',
            (string)(new Transformation())->resize(Pad::pad(80, 80)->background(Color::GREEN))
        );

        $this->assertEquals(
            'b_auto:border_contrast,c_pad,h_80,w_80',
            (string)(new Transformation())->resize(Pad::pad(80, 80)->background(AutoBackground::borderContrast()))
        );

        $this->assertEquals(
            'b_auto:predominant_gradient:2:diagonal_desc,c_pad,h_80,w_80',
            (string)(new Transformation())
                ->resize(
                    Pad::pad(80, 80)
                       ->background(
                           AutoBackground::gradientFade(
                               Gradient::PREDOMINANT_GRADIENT,
                               2,
                               GradientDirection::DIAGONAL_DESC
                           )
                       )
                )
        );

        //c_thumb,h_80,w_80,g_auto:cars:cats
        // TODO: implement SUBJECT
        //new Transformation().crop(Crop.thumb(80, 80, Gravity.object([Gravity.Objects.CARS, Gravity.Objects.CATS])))
    }

    public function testMultipleTransformations()
    {
        $this->assertEquals(
            'c_thumb,g_auto,h_80,w_80/c_scale,h_80,w_80',
            (string)(new Transformation())
                ->resize(Crop::thumbnail(80, 80, Gravity::auto()))
                ->resize(Scale::scale(80, 80))
        );
    }

    public function testEffects()
    {
        $this->assertEquals(
            'e_kuku:11:some_param',
            (string)(new Transformation())->effect(Effect::generic('kuku', 11, 'some_param'))
        );

        $this->assertEquals(
            'e_sepia',
            (string)(new Transformation())->effect(Effect::sepia())
        );

        $this->assertEquals(
            'e_saturation:82',
            (string)(new Transformation())->adjust(Adjust::saturation(82))
        );

        $this->assertEquals(
            'e_tint:100:green:red',
            (string)(new Transformation())->adjust(Adjust::tint(100, Color::GREEN, Color::RED))
        );

        $this->assertEquals(
            'co_green,e_shadow,x_10,y_h_div_50',
            (string)(new Transformation())->effect(
                Effect::shadow()->position(10, PVar::height()->divide()->numeric(50))->color(Color::GREEN)
            )
        );

        $this->assertEquals(
            'e_replace_color:maroon:80:2b38aa',
            (string)(new Transformation())->adjust(Adjust::replaceColor(Color::MAROON, 80, '2b38aa'))
        );

        $this->assertEquals(
            'co_orange,e_outline:inner:15:200',
            (string)(new Transformation())
                ->effect(
                    Effect::outline(Outline::INNER, 15, 200)->color(Color::ORANGE)
                )
        );
    }

    public function testBorder()
    {
        $this->assertEquals(
            'bo_4px_solid_hotpink',
            (string)(new Transformation())->border(Border::solid()->width(4)->color(Color::HOTPINK))
        );
    }


    public function testFormat()
    {
        $this->assertEquals(
            'f_auto',
            (string)(new Transformation())->format(Format::auto())
        );

        $this->assertEquals(
            'f_png',
            (string)(new Transformation())->format(Format::png())
        );

        $this->assertEquals(
            'f_mkv',
            (string)(new Transformation())->format(Format::mkv())
        );

        $this->assertEquals(
            'f_aac',
            (string)(new Transformation())->format(Format::aac())
        );
    }

    public function testQuality()
    {
        $this->assertEquals(
            'q_70',
            (string)(new Transformation())->quality(70)
        );

        $this->assertEquals(
            'q_70:420',
            (string)(new Transformation())->quality(Quality::level(70)->chromaSubSampling(Chroma::C420))
        );

        $this->assertEquals(
            'q_70:qmax_80',
            (string)(new Transformation())->quality(Quality::level(70)->quantization(80))
        );

        $this->assertEquals(
            'q_auto',
            (string)(new Transformation())->quality(Quality::auto())
        );

        $this->assertEquals(
            'fl_any_format,q_auto',
            (string)(new Transformation())->quality(Quality::auto()->anyFormat())
        );

        $this->assertEquals(
            'q_auto:good',
            (string)(new Transformation())->quality(Quality::good())
        );

        $this->assertEquals(
            'q_auto:some_new_param',
            (string)(new Transformation())->quality(Quality::auto('some_new_param'))
        );
    }

    public function testNamedTransformation()
    {
        $this->assertEquals(
            't_my_transformation',
            (string)(new Transformation())->namedTransformation('my_transformation')
        );
    }

    public function testGenericTransformation()
    {
        $genericT = 'c_crop,w_100,e_sepia/r_max/a_17';

        $this->assertEquals(
            $genericT,
            (string)(new Transformation($genericT))
        );

        $this->assertEquals(
            "$genericT/c_fill,h_80/$genericT",
            (string)(new Transformation($genericT))->resize(Fill::fill()->height(80))->addTransformation($genericT)
        );
    }

    public function testLayers()
    {
        $this->assertEquals(
            'l_blabla/fl_layer_apply',
            (string)(new Transformation())->overlay('blabla')
        );

        $this->assertEquals(
            'u_blabla/fl_layer_apply',
            (string)(new Transformation())->underlay(ImageLayer::image('blabla'))
        );

        $this->assertEquals(
            'l_blabla/c_scale,h_80,w_80/fl_layer_apply',
            (string)(new Transformation())->overlay(ImageLayer::image('blabla')->resize(Scale::scale(80, 80)))
        );

        $this->assertEquals(
            'l_blabla/c_fill,g_auto,h_80,w_80/fl_layer_apply,x_10,y_10',
            (string)(new Transformation())->overlay(
                (new ImageLayer('blabla'))->resize(Fill::fill(80, 80, Gravity::auto())),
                Position::absolute(10, 10)
            )
        );

        $this->assertEquals(
            'l_text:Arial_50:my_text/fl_layer_apply',
            (string)(new Transformation())->overlay(
                new TextLayer('my_text', new TextStyle(FontFamily::ARIAL, 50))
            )
        );

        $this->assertEquals(
            'l_text:Verdana_75_bold_italic_underline_letter_spacing_14:Flowers/fl_layer_apply',
            (string)(new Transformation())->overlay(
                ImageLayer::text(
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

        $this->assertEquals(
            'b_red,co_green,l_text:Impact_150:Your%20Logo%20Here/e_distort:arc:-120/fl_layer_apply,g_south,y_840',
            (string)(new Transformation())->overlay(
                ImageLayer::text(
                    'Your Logo Here',
                    new TextStyle(FontFamily::IMPACT, 150)
                )->color(Color::GREEN)->background(Color::RED)->reshape(Reshape::distortArc(-120)),
                Position::south(null, 840)
            )
        );

        $this->assertEquals(
            'l_lut:iwltbap_aspen.3dl/fl_layer_apply',
            (string)(new Transformation())->add3DLut('iwltbap_aspen.3dl')
        );
    }

    public function testPage()
    {
        $this->assertEquals(
            'pg_2',
            (string)(new Transformation())->getPage(2)
        );

        $this->assertEquals(
            'pg_3;5-7;9-',
            (string)(new Transformation())->getPage(Page::number(3), Page::range(5, 7), Page::range(9))
        );

        $this->assertEquals(
            'pg_3;5-7;9-',
            (string)(new Transformation())->getPage(3, '5-7', Page::range(9))
        );

        $this->assertEquals(
            'pg_name:record_cover;Shadow',
            (string)(new Transformation())->getLayer(PSDLayer::names('record_cover', 'Shadow'))
        );
    }

    public function testExpressions()
    {
        $this->assertEquals(
            'if_w_gt_1000/l_text:Arial_50:this%20is%20big/c_fill,h_80,w_80/fl_layer_apply/if_else/e_grayscale/if_end',
            (string)(new Transformation())
                ->ifCondition(PVar::width()->greaterThan()->numeric(1000))
                ->overlay(
                    Source::text('this is big', new TextStyle(FontFamily::ARIAL, 50))
                         ->resize(Resize::fill(80, 80))
                )
                ->ifElse()
                ->effect(Effect::grayscale())
                ->endIfCondition()
        );
    }

    public function testCustomParameter()
    {
        $this->assertEquals(
            'w_500',
            (string)(new Transformation())->addGenericParam('w', 500)
        );
    }

    public function testParametersBuilder()
    {
        $this->assertEquals(
            'w_100/h_200/ar_19:9/x_300/y_400/co_aquamarine/cn_cv',
            (string)(new Transformation())
                ->addAction(Parameter::width(100))
                ->addAction(Parameter::height(200))
                ->addAction(Parameter::aspectRatio(19, 9))
                ->addAction(Parameter::x(300))
                ->addAction(Parameter::y(400))
                ->addAction(Parameter::color(ColorValue::aquamarine()))
                ->addAction(Parameter::generic('cn', 'cv'))
        );
    }

    public function testVideo()
    {
        $this->assertEquals(
            'so_6.5,eo_10',
            (string)(new VideoTransformation())->trim(VideoRange::range(6.5, 10))
        );

        $this->assertEquals(
            'so_10p,du_30p',
            (string)(new VideoTransformation())->trim(VideoRange::range('10p')->duration('30p'))
        );

        $this->assertEquals(
            'c_fill,h_200,w_300/fl_splice,l_video:dog/so_0,eo_5/c_fill,h_200,w_300/fl_layer_apply',
            (string)(new VideoTransformation())
                ->resize(Fill::fill(300, 200))
                ->overlay(
                    VideoLayer::video('dog')
                              ->trim(VideoRange::range(0, 5))
                              ->resize(Fill::fill(300, 200))
                              ->setFlag(LayerFlag::splice())
                )
        );

        $this->assertEquals(
            'vc_h264:baseline:3.1',
            (string)(new VideoTransformation())
                ->transcode(VideoCodec::h264(VideoCodecProfile::BASELINE, VideoCodecLevel::VCL_31))
        );

        $this->assertEquals(
            'af_22050',
            (string)(new VideoTransformation())->transcode(AudioFrequency::freq22050())
        );

        $this->assertEquals(
            'af_iaf',
            (string)(new VideoTransformation())->transcode(AudioFrequency::iaf())
        );

        $this->assertEquals(
            'ac_none',
            (string)(new VideoTransformation())->transcode(AudioCodec::none())
        );

        $this->assertEquals(
            'l_subtitles:sample_sub_en.srt/fl_layer_apply',
            (string)(new VideoTransformation())->addSubtitles('sample_sub_en.srt')
        );
    }
}
