<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Transformation;

use Cloudinary\Test\Unit\UnitTestCase;
use Cloudinary\Transformation\Action;
use Cloudinary\Transformation\Adjust;
use Cloudinary\Transformation\Animated;
use Cloudinary\Transformation\AnimatedEdit;
use Cloudinary\Transformation\Argument\Color;
use Cloudinary\Transformation\Argument\Text\FontWeight;
use Cloudinary\Transformation\AspectRatio;
use Cloudinary\Transformation\AudioCodec;
use Cloudinary\Transformation\ChromaSubSampling;
use Cloudinary\Transformation\ColorSpace;
use Cloudinary\Transformation\CommonTransformation;
use Cloudinary\Transformation\Compass;
use Cloudinary\Transformation\CompassGravity;
use Cloudinary\Transformation\Conditional;
use Cloudinary\Transformation\Crop;
use Cloudinary\Transformation\Delivery;
use Cloudinary\Transformation\Effect;
use Cloudinary\Transformation\Expression\PVar;
use Cloudinary\Transformation\Extract;
use Cloudinary\Transformation\Flag;
use Cloudinary\Transformation\FocalGravity;
use Cloudinary\Transformation\FocusOn;
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\GenericResize;
use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\ImageTransformation;
use Cloudinary\Transformation\Overlay;
use Cloudinary\Transformation\Pad;
use Cloudinary\Transformation\Position;
use Cloudinary\Transformation\PsdTools;
use Cloudinary\Transformation\Qualifier;
use Cloudinary\Transformation\Quality;
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\Rotate;
use Cloudinary\Transformation\RoundCorners;
use Cloudinary\Transformation\Scale;
use Cloudinary\Transformation\Source;
use Cloudinary\Transformation\TextStyle;
use Cloudinary\Transformation\Transformation;
use Cloudinary\Transformation\Variable\Variable;
use InvalidArgumentException;

/**
 * Class TransformationTest
 */
final class TransformationTest extends UnitTestCase
{
    public function testTransformation()
    {
        $t = new Transformation();

        $t->resize(
            Pad::limitPad(17, 18)->background(Color::BLUE)
               ->gravity(CompassGravity::southWest())
        )
          ->adjust(Adjust::replaceColor(Color::GREEN, 17, Color::RED));

        $t->resize(
            Scale::scale(Qualifier::width(100), 200)->aspectRatio(AspectRatio::ignoreInitialAspectRatio())
                 ->setFlag(Flag::attachment('file.bin'))
        );

        $t->addAction(Effect::sepia(100));

        $t_expected = 'b_blue,c_lpad,g_south_west,h_18,w_17/e_replace_color:green:17:red/' .
                      'c_scale,fl_attachment:file%252Ebin,fl_ignore_aspect_ratio,h_200,w_100/e_sepia:100';
        self::assertEquals(
            $t_expected,
            (string)$t
        );

        $t2 = new Transformation();

        $t2->resize(Crop::thumbnail(100, 200, Gravity::auto(FocalGravity::ADVANCED_EYES)))
           ->adjust(Adjust::hue(99))
           ->adjust(Adjust::replaceColor(Color::PINK, 50, Color::CYAN));

        $t2_expected = 'c_thumb,g_auto:adv_eyes,h_200,w_100/e_hue:99/e_replace_color:pink:50:cyan';
        self::assertEquals(
            $t2_expected,
            (string)$t2
        );
        $t->addTransformation($t2)
          ->addTransformation($t2);

        self::assertEquals(
            "$t_expected/$t2_expected/$t2_expected",
            (string)$t
        );
    }

    public function testFloatValue()
    {
        self::assertEquals(
            'dpr_2.0',
            (string)Delivery::dpr(2.0)
        );
    }

    public function testTransformationResize()
    {
        $t = new Transformation();

        $t->scale(100, 200);

        self::assertEquals(
            'c_scale,h_200,w_100',
            (string)$t
        );

        $t->dpr(2.5);

        self::assertEquals(
            'c_scale,h_200,w_100/dpr_2.5',
            (string)$t
        );

        $t->crop(100, 200, null, 10, 20);

        self::assertEquals(
            'c_scale,h_200,w_100/dpr_2.5/c_crop,h_200,w_100,x_10,y_20',
            (string)$t
        );

        $t = new Transformation();

        $t->genericResize('jackie', 50, 175);

        self::assertEquals(
            'c_jackie,h_175,w_50',
            (string)$t
        );
    }

    public function testTransformationExpressions()
    {
        $t = new Transformation();

        $t->conditional(
            Conditional::ifCondition(
                PVar::width()->greaterThan()->int(997),
                Adjust::red(99)
            )->otherwise(Adjust::green(99))
        );

        self::assertEquals(
            'if_w_gt_997/e_red:99/if_else/e_green:99/if_end',
            (string)$t
        );

        $t = new Transformation();

        $t->conditional(Conditional::ifCondition('width > 997', Adjust::red(99))->otherwise(Adjust::green(99)));

        self::assertEquals(
            'if_w_gt_997/e_red:99/if_else/e_green:99/if_end',
            (string)$t
        );
    }

    public function testTransformationVariable()
    {
        $t = new Transformation();

        $t->addVariable('var', 17)
          ->addVariable(Variable::set('otherVariable', 19))
          ->addVariable(Variable::set('strVariable', 'stringValue'));

        self::assertEquals(
            '$var_17/$otherVariable_19/$strVariable_!stringValue!',
            (string)$t
        );
    }

    public function testTransformationFormat()
    {
        $t = new Transformation();

        $t->format(Format::flif());

        self::assertEquals(
            'f_flif',
            (string)$t
        );

        $t->format(Format::auto());

        self::assertEquals(
            'f_flif/f_auto',
            (string)$t
        );
    }

    public function testTransformationQuality()
    {
        $t = new Transformation();

        $t->quality((new Quality(99))->chromaSubSampling(ChromaSubSampling::chroma444()));

        self::assertEquals(
            'q_99:444',
            (string)$t
        );

        $t->quality(Quality::autoLow()->chromaSubSampling(ChromaSubSampling::chroma420()));

        self::assertEquals(
            'q_99:444/q_auto:low:420',
            (string)$t
        );
    }

    public function testTransformationShape()
    {
        $t = new Transformation();

        $t->roundCorners(17, 18, 19, 20);

        self::assertEquals(
            'r_17:18:19:20',
            (string)$t
        );

        $t->roundCorners(RoundCorners::max());

        self::assertEquals(
            'r_17:18:19:20/r_max',
            (string)$t
        );

        $t->rotate(17);

        self::assertEquals(
            'r_17:18:19:20/r_max/a_17',
            (string)$t
        );
    }

    public function testTransformationAddAction()
    {
        $t = new Transformation();

        $genericAction = 'c_crop,e_sepia,f_auto,g_auto';

        $t->addAction($genericAction);

        self::assertEquals(
            $genericAction,
            (string)$t
        );

        $t->addAction(Action::generic($genericAction)->addQualifier(RoundCorners::byRadius(17)));

        self::assertEquals(
            "$genericAction/$genericAction,r_17",
            (string)$t
        );

        $t->addAction(Rotate::deg90());

        self::assertEquals(
            "$genericAction/$genericAction,r_17/a_90",
            (string)$t
        );

        $t->addAction(AudioCodec::mp3()); // allow adding a parameter, that is wrapped with generic action inside

        self::assertEquals(
            "$genericAction/$genericAction,r_17/a_90/ac_mp3",
            (string)$t
        );

        $this->expectException(InvalidArgumentException::class);
        Action::generic("$genericAction/$genericAction");
    }

    public function testImageTransformationLayers()
    {
        self::assertEquals(
            'l_lut:iwltbap_aspen.3dl/fl_layer_apply',
            (string)(new ImageTransformation())->adjust(Adjust::by3dLut('iwltbap_aspen.3dl'))
        );
    }

    public function testSetFlags()
    {
        self::assertEquals(
            'fl_animated,fl_attachment:my_file%252Ebin,fl_tiff8_lzw',
            (string)(new Transformation())
                ->addAction(
                    Action::generic()
                          ->setFlag(Flag::animated())
                          ->setFlag(Flag::tiff8Lzw())
                          ->setFlag(Flag::attachment('my_file.bin'))

                )
        );
    }

    public function testSetFlagsWithBuilders()
    {
        self::assertEquals(
            'fl_attachment:my_file%252Ebin/fl_sanitize/fl_mono',
            (string)(new Transformation())->attachment('my_file.bin')->sanitize()->mono()
        );
    }

    public function testDefaultImage()
    {
        self::assertEquals(
            'd_avatar',
            (string)(new Transformation())->delivery(Delivery::defaultImage('avatar'))
        );
    }

    public function testTiffWithIgnoreMaskChannels()
    {
        self::assertEquals(
            'f_tiff,fl_ignore_mask_channels',
            (string)(new Transformation())->delivery(Delivery::format(Format::tiff())->ignoreMaskChannels())
        );
    }

    public function testColorSpace()
    {
        self::assertEquals(
            'cs_srgb',
            (string)(new Transformation())->colorSpace(ColorSpace::SRGB)
        );
        self::assertEquals(
            'cs_tinysrgb',
            (string)(new Transformation())->colorSpace(ColorSpace::tinysrgb())
        );
        self::assertEquals(
            'cs_cmyk',
            (string)(new Transformation())->colorSpace(ColorSpace::CMYK)
        );
        self::assertEquals(
            'cs_no_cmyk',
            (string)(new Transformation())->colorSpace(ColorSpace::noCmyk())
        );
        self::assertEquals(
            'cs_keep_cmyk',
            (string)(new Transformation())->colorSpace(ColorSpace::KEEP_CMYK)
        );
        self::assertEquals(
            'cs_icc:public_id',
            (string)(new Transformation())->colorSpace(ColorSpace::icc('public_id'))
        );
    }

    public function testDelay()
    {
        self::assertEquals(
            'dl_20',
            (string)(new Transformation())->delay(20)
        );
    }

    public function testPrefix()
    {
        self::assertEquals(
            'p_my_prefix',
            (string)(new Transformation())->prefix('my_prefix')
        );
    }

    public function testDensity()
    {
        self::assertStrEquals(
            'dn_20',
            (new Transformation())->density(20)
        );
    }

    public function testAnimated()
    {
        self::assertStrEquals(
            'dl_200,e_loop:2',
            (new Transformation())->animated(Animated::edit()->delay(200)->loop(2))
        );
    }



    public function testPage()
    {
        self::assertEquals(
            'pg_2',
            (string)(new Transformation())->extract(Extract::getPage(2))
        );

        self::assertEquals(
            'pg_2',
            (string)(new Transformation())->extract(Extract::getPage()->byNumber(2))
        );

        self::assertEquals(
            'pg_5;7',
            (string)(new Transformation())->extract(Extract::getPage(5, 7))
        );

        self::assertEquals(
            'pg_3;5-7;9-',
            (string)(new Transformation())->extract(Extract::getPage()->byNumber(3)->byRange(5, 7)->byRange(9))
        );

        self::assertEquals(
            'pg_3;5-7;9-',
            (string)(new Transformation())->extract(Extract::getPage(3, '5-7')->byRange(9))
        );

        self::assertEquals(
            'pg_all',
            (string)(new Transformation())->extract(Extract::getPage()->all())
        );
    }

    public function testFrame()
    {
        self::assertEquals(
            'pg_2',
            (string)(new Transformation())->extract(2)
        );

        self::assertEquals(
            'pg_2',
            (string)(new Transformation())->extract(Extract::getFrame(2))
        );

        self::assertEquals(
            'pg_2',
            (string)(new Transformation())->extract(Extract::getFrame()->byNumber(2))
        );
    }

    public function testPsdTools()
    {
        self::assertEquals(
            'pg_2',
            (string)(new Transformation())->psdTools(PsdTools::getLayer(2))
        );

        self::assertEquals(
            'pg_2;3;4;5',
            (string)(new Transformation())->psdTools(PsdTools::getLayer(2, 3, 4, 5))
        );

        self::assertEquals(
            'pg_2',
            (string)(new Transformation())->psdTools(PsdTools::getLayer()->byIndex(2))
        );

        self::assertEquals(
            'pg_name:record_cover;Shadow',
            (string)(new Transformation())->psdTools(PsdTools::getLayer()->byNames('record_cover', 'Shadow'))
        );

        self::assertEquals(
            'pg_name:record_cover-1',
            (string)(new Transformation())->psdTools(PsdTools::getLayer()->byName('record_cover', 1))
        );

        self::assertEquals(
            'pg_5-7',
            (string)(new Transformation())->psdTools(PsdTools::getLayer()->byRange(5, 7))
        );

        self::assertEquals(
            'pg_3;5-7;9-',
            (string)(new Transformation())->psdTools(PsdTools::getLayer()->byIndex(3)->byRange(5, 7)->byRange(9))
        );
    }


    public function testClippingPath()
    {
        self::assertEquals(
            'fl_clip,pg_2',
            (string)(new Transformation())->psdTools(PsdTools::clip(2))
        );

        self::assertEquals(
            'fl_clip,pg_name:myClippingPath',
            (string)(new Transformation())->psdTools(PsdTools::clip('name:myClippingPath'))
        );

        self::assertEquals(
            'fl_clip,pg_name:myClippingPath',
            (string)(new Transformation())->psdTools(PsdTools::clip()->byName('myClippingPath'))
        );

        self::assertEquals(
            'fl_clip_evenodd,pg_name:myEvenOddClippingPath',
            (string)(new Transformation())->psdTools(PsdTools::clip()->byName('myEvenOddClippingPath')->evenOdd())
        );
    }

    public function testSmartObject()
    {
        self::assertEquals(
            'pg_embedded:2',
            (string)(new Transformation())->psdTools(PsdTools::smartObject(2))
        );

        self::assertEquals(
            'pg_embedded:2',
            (string)(new Transformation())->psdTools(PsdTools::smartObject()->byIndex(2))
        );

        self::assertEquals(
            'pg_embedded:name:record_cover',
            (string)(new Transformation())->psdTools(PsdTools::smartObject()->byLayerName('record_cover'))
        );
        self::assertEquals(
            'pg_embedded:name:record_cover;Shadow',
            (string)(new Transformation())->psdTools(PsdTools::smartObject()->byLayerNames('record_cover', 'Shadow'))
        );
    }

    public function testCoupleSample()
    {
        self::assertStrEquals(
            'c_fill,g_south,h_250,w_400/' .
            'l_nice_couple/c_crop,fl_region_relative,g_faces,h_1.3,w_1.3/e_saturation:50/' .
            'e_vignette/c_scale,w_100/r_max/fl_layer_apply,g_center,x_-20,y_20/' .
            'l_balloon/c_scale,h_55/e_hue:-20/a_5/fl_layer_apply,x_30,y_5/' .
            'l_text:Cookie_40_bold:Love/co_rgb:F08,e_colorize/a_20/fl_layer_apply,x_-45,y_44/' .
            'c_crop,h_250,w_300,x_30/r_60',
            (new Transformation())
                ->resize(
                    Resize::fill()->width(400)->height(250)->gravity(Gravity::compass(Compass::south()))
                )
                ->overlay(
                    Overlay::source(
                        Source::image('nice_couple')
                              ->transformation(
                                  (new ImageTransformation())
                                      ->resize(
                                          Resize::crop()->width(1.3)->height(1.3)
                                                ->gravity(Gravity::focusOn(FocusOn::faces()))
                                                ->regionRelative()
                                      )
                                      ->adjust(Adjust::saturation()->level(50))
                                      ->effect(Effect::vignette())
                                      ->resize(Resize::scale()->width(100))
                                      ->roundCorners(RoundCorners::max())
                              )
                    )
                           ->position(
                               (new Position())
                                   ->gravity(Gravity::compass(Compass::center()))
                                   ->offsetX(-20)->offsetY(20)
                           )
                )
                ->overlay(
                    Overlay::source(
                        Source::image('balloon')
                              ->transformation(
                                  (new ImageTransformation())
                                      ->resize(Resize::scale()->height(55))
                                      ->adjust(Adjust::hue()->level(-20))
                                      ->rotate(Rotate::byAngle(5))
                              )
                    )
                           ->position(
                               (new Position())
                                   ->offsetX(30)->offsetY(5)
                           )
                )
                ->overlay(
                    Overlay::source(
                        Source::text(
                            'Love',
                            (new TextStyle('Cookie', 40))
                                ->fontWeight(FontWeight::bold())
                        )
                              ->transformation(
                                  (new ImageTransformation())
                                      ->effect(Effect::colorize()->color(Color::rgb('F08')))
                                      ->rotate(Rotate::byAngle(20))
                              )
                    )
                           ->position(
                               (new Position())
                                   ->offsetX(-45)->offsetY(44)
                           )
                )
                ->resize(Resize::crop()->width(300)->height(250)->x(30))
                ->roundCorners(RoundCorners::byRadius(60))
        );
    }

    /**
     * Tests that user variable names containing predefined names are not affected by normalization
     */
    public function testUserVariableNamesContainingPredefinedNamesAreNotAffected()
    {
        $transformation = (new Transformation())
            ->addVariable('$mywidth', 100)
            ->addVariable('$aheight', 300)
            ->resize(
                GenericResize::generic(
                    '',
                    '3 + $mywidth * 3 + 4 / 2 * initialWidth * $mywidth',
                    '3 * initialHeight + $aheight'
                )
            );

        self::assertEquals(
            '$mywidth_100/$aheight_300/h_3_mul_ih_add_$aheight,w_3_add_$mywidth_mul_3_add_4_div_2_mul_iw_mul_$mywidth',
            (string)$transformation
        );
    }
}
