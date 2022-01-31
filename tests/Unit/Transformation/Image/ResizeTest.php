<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Transformation\Image;

use Cloudinary\Transformation\Argument\Color;
use Cloudinary\Transformation\AspectRatio;
use Cloudinary\Transformation\AutoGravity;
use Cloudinary\Transformation\CompassGravity;
use Cloudinary\Transformation\CompassPosition;
use Cloudinary\Transformation\Crop;
use Cloudinary\Transformation\Fill;
use Cloudinary\Transformation\FillPad;
use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\Pad;
use Cloudinary\Transformation\Qualifier;
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\ResizeMode;
use Cloudinary\Transformation\Scale;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Class ResizeTest
 */
final class ResizeTest extends TestCase
{
    public function testScale()
    {
        $scale = Scale::scale();

        self::assertEquals(
            'c_scale',
            (string)$scale
        );
//        self::assertEquals(
//            '{"name":"resize","qualifiers":{"crop":"scale"}}',
//            json_encode($scale)
//        );

        $scaleWithParams = Scale::scale(100, 200);

        self::assertEquals(
            'c_scale,h_200,w_100',
            (string)$scaleWithParams
        );

//        self::assertEquals(
//            '{"name":"resize","qualifiers":{"crop":"scale","dimensions":{"width":100,"height":200}}}',
//            json_encode($scaleWithParams)
//        );

        $scaleWithBuiltParams = Scale::scale()->width(100)->height(200)->aspectRatio(0.5);

        self::assertEquals(
            'ar_0.5,c_scale,h_200,w_100',
            (string)$scaleWithBuiltParams
        );

        $scaleWithLiquidGravity = Scale::scale(100, 200)
                                       ->liquidRescaling()
                                       ->aspectRatio(AspectRatio::ignoreInitialAspectRatio());

        self::assertEquals(
            'c_scale,fl_ignore_aspect_ratio,g_liquid,h_200,w_100',
            (string)$scaleWithLiquidGravity
        );

//        self::assertEquals(
//            '{"name":"resize","qualifiers":{"crop":"scale","dimensions":{"width":100,"height":200},'.
//            '"gravity":"liquid"},'.
//            '"flags":{"flag":"ignore_aspect_ratio"}}',
//            json_encode($scaleWithLiquidGravity)
//        );

        $limitFit = Scale::limitFit();
        self::assertEquals(
            'c_limit',
            (string)$limitFit
        );

//        self::assertEquals(
//            '{"name":"resize","qualifiers":{"crop":"limit"}}',
//            json_encode($limitFit)
//        );

        $fit = Scale::fit();
        self::assertEquals(
            'c_fit',
            (string)$fit
        );

//        self::assertEquals(
//            '{"name":"resize","qualifiers":{"crop":"fit"}}',
//            json_encode($fit)
//        );

        self::assertEquals(
            'c_mfit',
            (string)Scale::minimumFit()
        );
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Liquid Rescaling is not supported for
     */
    public function testLiquidForUnsupportedCropMode()
    {
        Scale::limitFit(100, 200)->liquidRescaling();
    }

    public function testPad()
    {
        $pad = Pad::pad();

        self::assertEquals(
            'c_pad',
            (string)$pad
        );

        self::assertEquals(
            '{"name":"resize","qualifiers":[{"crop_mode":"pad"}]}',
            json_encode($pad)
        );

        $padWithParams = Pad::minimumPad(100, 200);

        self::assertEquals(
            'c_mpad,h_200,w_100',
            (string)$padWithParams
        );

//        self::assertEquals(
//            '{"name":"resize","qualifiers":[{"crop":"mpad","dimensions":{"width":100,"height":200}}}]',
//            json_encode($padWithParams)
//        );

        $padWithBuiltParams = Pad::pad(100, 200)->offset(50, 100)->background(Color::RED);

        self::assertEquals(
            'b_red,c_pad,h_200,w_100,x_50,y_100',
            (string)$padWithBuiltParams
        );

        $padWithGravity = Pad::pad(100, 200)->gravity(Gravity::northWest());

        self::assertEquals(
            'c_pad,g_north_west,h_200,w_100',
            (string)$padWithGravity
        );

        $lpadWithPosition = Pad::limitPad(100, 200)->position(
            new CompassPosition(Gravity::northWest(), 50, 100)
        );

        self::assertEquals(
            'c_lpad,g_north_west,h_200,w_100,x_50,y_100',
            (string)$lpadWithPosition
        );
    }

    public function testFillPad()
    {
        $fillPad = FillPad::fillPad(100, 200)->background(Color::RED)->offsetY(20);

        self::assertEquals(
            'b_red,c_fill_pad,g_auto,h_200,w_100,y_20',
            (string)$fillPad
        );
    }

    public function testFillPadNonAutoGravity()
    {
        $this->expectException(InvalidArgumentException::class);

        Resize::fillPad(100, 200, new CompassGravity());
    }

    public function testImagga()
    {
        $imaggaCrop = Resize::imaggaCrop(100, 200);

        self::assertEquals(
            'c_imagga_crop,h_200,w_100',
            (string)$imaggaCrop
        );

        $imaggaScale = Resize::imaggaScale(100, 200);

        self::assertEquals(
            'c_imagga_scale,h_200,w_100',
            (string)$imaggaScale
        );
    }

    public function testFill()
    {
        $fill = Fill::fill(100, 200, Gravity::auto());

        self::assertEquals(
            'c_fill,g_auto,h_200,w_100',
            (string)$fill
        );


        self::assertEquals(
            'c_fill,g_auto,h_200,w_100,x_50',
            (string)$fill->x(50)
        );

        $limitFill = Fill::limitFill(100, 200, Gravity::auto());

        self::assertEquals(
            'c_lfill,g_auto,h_200,w_100',
            (string)$limitFill
        );
    }

    public function testCrop()
    {
        $crop = Crop::crop(100, 200)->x(10)->y(20);

        self::assertEquals(
            'c_crop,h_200,w_100,x_10,y_20',
            (string)$crop
        );

        $thumb = Crop::thumbnail(100, 200, Gravity::auto())->zoom(0.5);

        self::assertEquals(
            'c_thumb,g_auto,h_200,w_100,z_0.5',
            (string)$thumb
        );
    }

    public function testResize()
    {
        /** @noinspection PhpUndefinedMethodInspection */
        self::assertEquals(
            'c_scale,fl_ignore_aspect_ratio,g_liquid,h_200,w_100',
            (string)Resize::scale(100, 200)->aspectRatio(AspectRatio::ignoreInitialAspectRatio())->liquidRescaling()
        );

        self::assertEquals(
            'c_crop,h_200,w_100,x_10,y_20',
            (string)Resize::crop(100, 200)->x(10)->y(20)
        );

        self::assertEquals(
            'c_custom,cu_v1:17,h_200,w_100,x_10,y_20',
            (string)Resize::generic('custom', 100, 200)->addQualifier(Qualifier::generic('cu', 'v1', 17))
                          ->offsetX(10)->offsetY(20)
        );

        self::assertEquals(
            'c_crop,fl_region_relative,h_200,w_100',
            (string)Resize::crop(100, 200)->resizeMode(ResizeMode::regionRelative())
        );

        self::assertEquals(
            'c_crop,fl_region_relative,h_0.8,w_0.5',
            (string)Resize::crop(0.5, 0.8)->regionRelative()
        );

        self::assertEquals(
            'c_crop,fl_relative,h_0.8,w_0.5',
            (string)Resize::crop(0.5, 0.8)->relative()
        );

        self::assertEquals(
            'c_crop,g_auto:ocr_text_avoid,h_200,w_100',
            (string)Resize::crop(100, 200)->gravity(Gravity::auto(AutoGravity::object(Gravity::ocrText())->avoid()))
        );

        self::assertEquals(
            'c_crop,g_auto:ocr_text_30,h_200,w_100',
            (string)Resize::crop(100, 200)->gravity(Gravity::auto(AutoGravity::object(Gravity::ocrText())->weight(30)))
        );

        self::assertEquals(
            'c_crop,h_70,w_50,z_0.5',
            (string)Resize::crop(50, 70)->zoom(0.5)
        );
    }
}
