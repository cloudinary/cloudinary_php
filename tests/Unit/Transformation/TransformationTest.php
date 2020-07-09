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
use Cloudinary\Transformation\Argument\Color;
use Cloudinary\Transformation\AudioCodec;
use Cloudinary\Transformation\Chroma;
use Cloudinary\Transformation\ColorSpace;
use Cloudinary\Transformation\CompassGravity;
use Cloudinary\Transformation\Crop;
use Cloudinary\Transformation\Effect;
use Cloudinary\Transformation\Expression\PVar;
use Cloudinary\Transformation\Flag;
use Cloudinary\Transformation\FocalGravity;
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\Frame;
use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\ImageTransformation;
use Cloudinary\Transformation\Pad;
use Cloudinary\Transformation\Page;
use Cloudinary\Transformation\Parameter;
use Cloudinary\Transformation\PSDLayer;
use Cloudinary\Transformation\Quality;
use Cloudinary\Transformation\Rotate;
use Cloudinary\Transformation\RoundCorners;
use Cloudinary\Transformation\Scale;
use Cloudinary\Transformation\SmartObject;
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
            Scale::scale(Parameter::width(100), 200)->ignoreAspectRatio(true)
                 ->setFlag(Flag::attachment('file.bin'))
        );

        $t->addAction(Effect::sepia(100));

        $t_expected = 'b_blue,c_lpad,g_south_west,h_18,w_17/e_replace_color:green:17:red/' .
                      'c_scale,fl_attachment:file%252Ebin.ignore_aspect_ratio,h_200,w_100/e_sepia:100';
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

        $t->ifCondition(PVar::width()->greaterThan()->int(997))
          ->adjust(Adjust::red(99))
          ->ifElse()
          ->adjust(Adjust::green(99))
          ->endIfCondition();

        self::assertEquals(
            'if_w_gt_997/e_red:99/if_else/e_green:99/if_end',
            (string)$t
        );

        $t = new Transformation();

        $t->ifCondition('width > 997')
          ->adjust(Adjust::red(99))
          ->ifElse()
          ->adjust(Adjust::green(99))
          ->endIfCondition();

        self::assertEquals(
            'if_w_gt_997/e_red:99/if_else/e_green:99/if_end',
            (string)$t
        );
    }

    public function testTransformationVariable()
    {
        $t = new Transformation();

        $t->variable('var', 17)->addAction(Variable::define('otherVariable', 19));

        self::assertEquals(
            '$var_17/$otherVariable_19',
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

        $t->quality((new Quality(99))->chromaSubSampling(Chroma::C444));

        self::assertEquals(
            'q_99:444',
            (string)$t
        );

        $t->quality(Quality::low()->chromaSubSampling(Chroma::C420));

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

        $t->addAction(Action::generic($genericAction)->addParameter(RoundCorners::radius(17)));

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
        $this->assertEquals(
            'l_lut:iwltbap_aspen.3dl/fl_layer_apply',
            (string)(new ImageTransformation())->add3DLut('iwltbap_aspen.3dl')
        );
    }

    public function testSetFlags()
    {
        $this->assertEquals(
            'fl_animated.attachment:my_file%252Ebin.tiff8_lzw',
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
        $this->assertEquals(
            'fl_attachment:my_file.bin/fl_sanitize/fl_mono',
            (string)(new Transformation())->attachment('my_file.bin')->sanitize()->mono()
        );
    }

    public function testDefaultImage()
    {
        $this->assertEquals(
            'd_avatar',
            (string)(new Transformation())->defaultImage('avatar')
        );
    }

    public function testColorSpace()
    {
        $this->assertEquals(
            'cs_srgb',
            (string)(new Transformation())->colorSpace(ColorSpace::SRGB)
        );
        $this->assertEquals(
            'cs_tinysrgb',
            (string)(new Transformation())->colorSpace(ColorSpace::TINY_SRGB)
        );
        $this->assertEquals(
            'cs_cmyk',
            (string)(new Transformation())->colorSpace(ColorSpace::CMYK)
        );
        $this->assertEquals(
            'cs_no_cmyk',
            (string)(new Transformation())->colorSpace(ColorSpace::NO_CMYK)
        );
        $this->assertEquals(
            'cs_keep_cmyk',
            (string)(new Transformation())->colorSpace(ColorSpace::KEEP_CMYK)
        );
        $this->assertEquals(
            'cs_icc:public_id',
            (string)(new Transformation())->colorSpace(ColorSpace::icc('public_id'))
        );
    }

    public function testDelay()
    {
        $this->assertEquals(
            'dl_20',
            (string)(new Transformation())->delay(20)
        );
    }

    public function testPrefix()
    {
        $this->assertEquals(
            'p_my_prefix',
            (string)(new Transformation())->prefix('my_prefix')
        );
    }

    public function testDensity()
    {
        $this->assertStrEquals(
            'dn_20',
            (new Transformation())->density(20)
        );
    }


    public function testPage()
    {
        $this->assertEquals(
            'pg_2',
            (string)(new Transformation())->getPage(2)
        );

        $this->assertEquals(
            'pg_2',
            (string)(new Transformation())->getPage(Page::number(2))
        );

        $this->assertEquals(
            'pg_5-7',
            (string)(new Transformation())->getPage(Page::range(5, 7))
        );

        $this->assertEquals(
            'pg_3;5-7;9-',
            (string)(new Transformation())->getPage(Page::number(3), Page::range(5, 7), Page::range(9))
        );

        $this->assertEquals(
            'pg_3;5-7;9-',
            (string)(new Transformation())->getPage(3, '5-7', Page::range(9))
        );
    }

    public function testFrame()
    {
        $this->assertEquals(
            'pg_2',
            (string)(new Transformation())->getFrame(2)
        );

        $this->assertEquals(
            'pg_2',
            (string)(new Transformation())->getFrame(Frame::number(2))
        );
    }

    public function testLayer()
    {
        $this->assertEquals(
            'pg_2',
            (string)(new Transformation())->getLayer(2)
        );

        $this->assertEquals(
            'pg_2;3;4;5',
            (string)(new Transformation())->getLayer(2, 3, 4, 5)
        );

        $this->assertEquals(
            'pg_2',
            (string)(new Transformation())->getLayer(PSDLayer::index(2))
        );

        $this->assertEquals(
            'pg_name:record_cover;Shadow',
            (string)(new Transformation())->getLayer(PSDLayer::names('record_cover', 'Shadow'))
        );

        $this->assertEquals(
            'pg_name:record_cover-1',
            (string)(new Transformation())->getLayer(PSDLayer::name('record_cover', 1))
        );

        $this->assertEquals(
            'pg_5-7',
            (string)(new Transformation())->getLayer(PSDLayer::range(5, 7))
        );

        $this->assertEquals(
            'pg_3;5-7;9-',
            (string)(new Transformation())->getLayer(
                PSDLayer::index(3),
                PSDLayer::range(5, 7),
                PSDLayer::range(9)
            )
        );
    }


    public function testClippingPath()
    {
        $this->assertEquals(
            'fl_clip,pg_2',
            (string)(new Transformation())->clip(2)
        );

        $this->assertEquals(
            'fl_clip,pg_name:myClippingPath',
            (string)(new Transformation())->clip('name:myClippingPath')
        );

        $this->assertEquals(
            'fl_clip,pg_name:myClippingPath',
            (string)(new Transformation())->clip(PSDLayer::name('myClippingPath'))
        );

        $this->assertEquals(
            'fl_clip_evenodd,pg_name:myEvenOddClippingPath',
            (string)(new Transformation())->clipEvenOdd(PSDLayer::name('myEvenOddClippingPath'))
        );
    }

    public function testSmartObject()
    {
        $this->assertEquals(
            'pg_embedded:2',
            (string)(new Transformation())->getSmartObject(2)
        );

        $this->assertEquals(
            'pg_embedded:2',
            (string)(new Transformation())->getSmartObject(SmartObject::number(2))
        );

        $this->assertEquals(
            'pg_embedded:name:record_cover;Shadow',
            (string)(new Transformation())->getSmartObject(SmartObject::names('record_cover', 'Shadow'))
        );

        // uses value from PSDLayer parameter
        $this->assertEquals(
            'pg_embedded:name:record_cover;Shadow',
            (string)(new Transformation())->getSmartObject(PSDLayer::names('record_cover', 'Shadow'))
        );

        // uses value from Page parameter
        $this->assertEquals(
            'pg_embedded:2',
            (string)(new Transformation())->getSmartObject(Page::number(2))
        );
    }
}
