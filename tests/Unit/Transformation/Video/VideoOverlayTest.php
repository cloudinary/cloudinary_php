<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Transformation\Video;

use Cloudinary\Transformation\Compass;
use Cloudinary\Transformation\CompassPosition;
use Cloudinary\Transformation\Concatenate;
use Cloudinary\Transformation\Crop;
use Cloudinary\Transformation\Fill;
use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\ImageSource;
use Cloudinary\Transformation\Timeline;
use Cloudinary\Transformation\VideoEdit;
use Cloudinary\Transformation\VideoOverlay;
use Cloudinary\Transformation\VideoSource;
use Cloudinary\Transformation\VideoTransformation;
use PHPUnit\Framework\TestCase;

/**
 * Class VideoOverlayTest
 */
final class VideoOverlayTest extends TestCase
{
    public function testVideoOverlay()
    {
        $t = new VideoTransformation();

        $t->resize(Crop::crop(100, 200)->gravity(Gravity::compass(Compass::south())));

        $tExpected = 'c_crop,g_south,h_200,w_100';

        $il = (new ImageSource('test'))->transformation($t);

        $lExpected = "test/$tExpected/fl_layer_apply";

        $p = new CompassPosition(Compass::southWest(), 17, 19);

        $pExpected = 'g_south_west,x_17,y_19';

        $tlp = Timeline::position(0, 10);

        $tlpExpected = 'so_0,eo_10';

        self::assertEquals(
            "l_$lExpected,$pExpected,$tlpExpected",
            (string)new VideoOverlay($il, $p, $tlp)
        );

        self::assertEquals(
            "l_$lExpected,$pExpected,$tlpExpected",
            (string)(new VideoOverlay($il))->position($p)->timeline($tlp)
        );
    }

    public function testVideoConcatenate()
    {
        $resize    = Fill::fill(300, 200);
        $resizeStr = 'c_fill,h_200,w_300';

        self::assertEquals(
            "$resizeStr/so_0,eo_3/fl_splice,l_video:kitten_fighting/so_2,eo_5/$resizeStr/fl_layer_apply",
            (string)(new VideoTransformation())
                ->resize($resize)
                ->trim(Timeline::position(0, 3))
                ->concatenate(
                    Concatenate::videoSource('kitten_fighting')->trim(VideoEdit::trim(2, 5))->resize($resize)
                )
        );

        self::assertEquals(
            "du_5,fl_splice,l_video:kitten_fighting/so_2,eo_5/$resizeStr/" .
            "e_transition,l_video:transition/fl_layer_apply/fl_layer_apply,so_0",
            (string)(new VideoTransformation())
                ->videoEdit(
                    VideoEdit::concatenate(
                        Concatenate::videoSource('kitten_fighting')->trim(Timeline::position(2, 5))->resize($resize)
                    )->prepend()->transition("transition")->duration(5)
                )
        );
    }

    public function testVideoCutter()
    {
        $resize    = Fill::fill(300, 200);
        $resizeStr = 'c_fill,h_200,w_300';

        self::assertEquals(
            "$resizeStr/so_0,eo_3/fl_cutter,l_video:kitten_fighting/so_2,eo_5/$resizeStr/fl_layer_apply",
            (string)(new VideoTransformation())
                ->resize($resize)
                ->trim(Timeline::position(0, 3))
                ->cutter(
                    VideoSource::video('kitten_fighting')
                               ->trim(Timeline::position(2, 5))
                               ->resize($resize)
                )
        );
    }
}
