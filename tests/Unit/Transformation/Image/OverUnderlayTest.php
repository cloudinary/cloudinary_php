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
use Cloudinary\Transformation\Argument\Text\TextStyle;
use Cloudinary\Transformation\BlendMode;
use Cloudinary\Transformation\Crop;
use Cloudinary\Transformation\Effect;
use Cloudinary\Transformation\FocalGravity;
use Cloudinary\Transformation\FocalPosition;
use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\ImageLayer;
use Cloudinary\Transformation\LayerStackPosition;
use Cloudinary\Transformation\Overlay;
use Cloudinary\Transformation\Position;
use Cloudinary\Transformation\Transformation;
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

        $l = ImageLayer::image('test')->transformation($t);

        $lExpected = "test/$tExpected/fl_layer_apply";

        $p = Position::southWest(17, 19);

        $pExpected = 'g_south_west,x_17,y_19';

        $this->assertEquals(
            "l_$lExpected,$pExpected",
            (string)new Overlay($l, $p)
        );

        $focalPosition = new FocalPosition(Gravity::auto(FocalGravity::ADVANCED_FACES));

        $textLayer = ImageLayer::text('Hello world', new TextStyle(FontFamily::ARIAL, 14));

        $textLayer->setStackPosition(LayerStackPosition::UNDERLAY);

        $this->assertEquals(
            'u_text:Arial_14:Hello%20world/fl_layer_apply,g_auto:adv_faces',
            (string)(new Overlay($textLayer))->position($focalPosition)
        );
    }

    public function testOverlayBlendMode()
    {
        $this->assertEquals(
            'l_test/fl_layer_apply,x_10,y_20,e_screen',
            (string)(new Transformation())
                ->overlay(ImageLayer::image('test'), Position::absolute(10, 20), BlendMode::screen())
        );

        $this->assertEquals(
            'l_test/fl_layer_apply,x_10,y_20,e_mask',
            (string)(new Transformation())
                ->overlay(ImageLayer::image('test'), Position::absolute(10, 20), BlendMode::MASK)
        );

        $this->assertEquals(
            'u_test/fl_layer_apply,x_10,y_20,e_multiply',
            (string)(new Transformation())
                ->underlay(ImageLayer::image('test'), Position::absolute(10, 20), 'multiply')
        );

        $this->assertEquals(
            'l_test/fl_layer_apply,e_anti_removal',
            (string)(new Overlay('test'))->blendMode(BlendMode::antiRemoval())
        );
    }

    public function testOverlayOtherImage()
    {
        $this->assertEquals(
            'l_some:test:image/e_blur_faces/fl_layer_apply,x_100,y_100',
            (string)(new Transformation())->overlay(
                (new Image('some/test/image'))->effect(Effect::blurFaces()),
                Position::absolute(100, 100)
            )
        );
    }
}
