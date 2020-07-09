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

use Cloudinary\Transformation\CompassGravity;
use Cloudinary\Transformation\CompassPosition;
use Cloudinary\Transformation\Crop;
use Cloudinary\Transformation\Fill;
use Cloudinary\Transformation\ImageLayer;
use Cloudinary\Transformation\Parameter\VideoRange\VideoRange;
use Cloudinary\Transformation\VideoLayer;
use Cloudinary\Transformation\VideoOverlay;
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

        $t->resize(Crop::crop(100, 200)->gravity(new CompassGravity(CompassGravity::SOUTH)));

        $tExpected = 'c_crop,g_south,h_200,w_100';

        $il = (new ImageLayer('test'))->transformation($t);

        $lExpected = "test/$tExpected/fl_layer_apply";

        $p = new CompassPosition(CompassGravity::SOUTH_WEST, 17, 19);

        $pExpected = 'g_south_west,x_17,y_19';

        $tlp = VideoRange::range(0, 10);

        $tlpExpected = 'so_0,eo_10';

        $this->assertEquals(
            "l_$lExpected,$pExpected,$tlpExpected",
            (string)new VideoOverlay($il, $p, $tlp)
        );

        $this->assertEquals(
            "l_$lExpected,$pExpected,$tlpExpected",
            (string)(new VideoOverlay($il))->position($p)->timelinePosition($tlp)
        );
    }

    public function testVideoConcatenate()
    {
        $resize = Fill::fill(300, 200);
        $resizeStr= 'c_fill,h_200,w_300';

        $this->assertEquals(
            "$resizeStr/so_0,eo_3/fl_splice,l_video:kitten_fighting/so_2,eo_5/$resizeStr/fl_layer_apply",
            (string)(new VideoTransformation())
                ->resize($resize)
                ->trim(VideoRange::range(0, 3))
                ->concatenate(
                    VideoLayer::video('kitten_fighting')
                              ->trim(VideoRange::range(2, 5))
                              ->resize($resize)
                )
        );
    }

    public function testVideoCutter()
    {
        $resize = Fill::fill(300, 200);
        $resizeStr= 'c_fill,h_200,w_300';

        $this->assertEquals(
            "$resizeStr/so_0,eo_3/fl_cutter,l_video:kitten_fighting/so_2,eo_5/$resizeStr/fl_layer_apply",
            (string)(new VideoTransformation())
                ->resize($resize)
                ->trim(VideoRange::range(0, 3))
                ->cutter(
                    VideoLayer::video('kitten_fighting')
                              ->trim(VideoRange::range(2, 5))
                              ->resize($resize)
                )
        );
    }
}
