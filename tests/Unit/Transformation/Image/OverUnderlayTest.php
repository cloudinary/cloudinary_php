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

use Cloudinary\Asset\Image;
use Cloudinary\Transformation\Adjust;
use Cloudinary\Transformation\Argument\Color;
use Cloudinary\Transformation\Argument\Text\FontFamily;
use Cloudinary\Transformation\BlendMode;
use Cloudinary\Transformation\Crop;
use Cloudinary\Transformation\Effect;
use Cloudinary\Transformation\FocalGravity;
use Cloudinary\Transformation\FocalPosition;
use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\ImageSource;
use Cloudinary\Transformation\LayerStackPosition;
use Cloudinary\Transformation\ImageOverlay;
use Cloudinary\Transformation\Overlay;
use Cloudinary\Transformation\Position;
use Cloudinary\Transformation\Region;
use Cloudinary\Transformation\Source;
use Cloudinary\Transformation\TextStyle;
use Cloudinary\Transformation\Transformation;
use Cloudinary\Transformation\Underlay;
use PHPUnit\Framework\TestCase;

/**
 * Class OverUnderlayTest
 */
final class OverUnderlayTest extends TestCase
{
    public function testOverUnderlayLayer()
    {
        $t = new Transformation();

        $t->resize(Crop::thumbnail(100, 200, Gravity::auto(FocalGravity::ADVANCED_EYES)))
          ->adjust(Adjust::hue(99))
          ->adjust(Adjust::replaceColor(Color::PINK, 50, Color::CYAN));

        $tExpected = 'c_thumb,g_auto:adv_eyes,h_200,w_100/e_hue:99/e_replace_color:pink:50:cyan';

        $l = ImageSource::image('test')->transformation($t);

        $lExpected = "test/$tExpected/fl_layer_apply";

        $p = Position::southWest(17, 19);

        $pExpected = 'g_south_west,x_17,y_19';

        self::assertEquals(
            "l_$lExpected,$pExpected",
            (string)new ImageOverlay($l, $p)
        );

        $focalPosition = new FocalPosition(Gravity::auto(FocalGravity::ADVANCED_FACES));

        $textLayer = ImageSource::text('Hello world', new TextStyle(FontFamily::ARIAL, 14));

        $textLayer->setStackPosition(LayerStackPosition::UNDERLAY);

        self::assertEquals(
            'u_text:Arial_14:Hello%20world/fl_layer_apply,g_auto:adv_faces',
            (string)(new ImageOverlay($textLayer))->position($focalPosition)
        );
    }

    public function testUnderlay()
    {
        $t = new Transformation();
        $source = Source::fetch('http://image.url');
        $underlay = Underlay::source($source);
        $t->underlay($underlay);

        self::assertEquals(
            "u_fetch:aHR0cDovL2ltYWdlLnVybA==/fl_layer_apply",
            (string)$t
        );
    }

    public function testOverlayBlendMode()
    {
        self::assertEquals(
            'l_test/e_screen,fl_layer_apply,x_10,y_20',
            (string)(new Transformation())
                ->overlay(Overlay::source('test')->position(Position::absolute(10, 20))->blendMode(BlendMode::screen()))
        );

        self::assertEquals(
            'l_test/e_mask,fl_layer_apply,x_10,y_20',
            (string)(new Transformation())
                ->overlay(Overlay::source('test')->position(Position::absolute(10, 20))->blendMode(BlendMode::mask()))
        );

        self::assertEquals(
            'u_test/e_multiply,fl_layer_apply,x_10,y_20',
            (string)(new Transformation())
                ->underlay(Overlay::source('test')->position(Position::absolute(10, 20))->blendMode('multiply'))
        );

        self::assertEquals(
            'l_test/e_anti_removal,fl_layer_apply',
            (string)(Overlay::source('test')->blendMode(BlendMode::antiRemoval()))
        );
    }

    public function testOverlayPosition()
    {
        self::assertEquals(
            'l_test/fl_layer_apply,fl_no_overflow,fl_tiled,x_10,y_20',
            (string)(new Transformation())
                ->overlay(Overlay::source('test')->position(Position::absolute(10, 20)->tiled()->allowOverflow(false)))
        );
    }

    public function testOverlayOtherImage()
    {
        self::assertEquals(
            'l_some:test:image/e_blur_faces/fl_layer_apply,x_100,y_100',
            (string)(new Transformation())->overlay(
                (new Image('some/test/image'))->effect(Effect::blur()->region(Region::faces())),
                Position::absolute(100, 100)
            )
        );
    }
}
