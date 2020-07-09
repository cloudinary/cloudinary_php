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

use Cloudinary\Test\Unit\UnitTestCase;
use Cloudinary\Transformation\Effect;
use Cloudinary\Transformation\Fps;
use Cloudinary\Transformation\Pad;
use Cloudinary\Transformation\Parameter\Dimensions\Width;
use Cloudinary\Transformation\Parameter\VideoRange\VideoRange;
use Cloudinary\Transformation\Scale;
use Cloudinary\Transformation\StreamingProfile;
use Cloudinary\Transformation\SubtitlesLayer;
use Cloudinary\Transformation\Transformation;
use Cloudinary\Transformation\VideoEffect;
use Cloudinary\Transformation\VideoTransformation;

/**
 * Class VideoTransformationTest
 */
final class VideoTransformationTest extends UnitTestCase
{
    public function testVideoTransformation()
    {
        $t = new VideoTransformation();

        $t->resize(Scale::scale(new Width(100), 200)->ignoreAspectRatio(true))->resize(Pad::limitPad(50))
          ->rotate(17);

        $t_expected = 'c_scale,fl_ignore_aspect_ratio,h_200,w_100/c_lpad,w_50/a_17';
        self::assertEquals(
            $t_expected,
            (string)$t
        );

        $t->trim(VideoRange::range('auto', '90%', 10.1));

        $t2_expected = $t_expected . '/so_auto,eo_90p,du_10.1';
        self::assertEquals(
            $t2_expected,
            (string)$t
        );

        $t->roundCorners(17);

        $t3_expected = $t2_expected . '/r_17';
        self::assertEquals(
            $t3_expected,
            (string)$t
        );

        $t->rotate(17);

        $t4_expected = $t3_expected . '/a_17';
        self::assertEquals(
            $t4_expected,
            (string)$t
        );

        $t->overlay('dog');

        $t5_expected = $t4_expected . '/l_video:dog/fl_layer_apply';
        self::assertEquals(
            $t5_expected,
            (string)$t
        );
    }

    public function testVideoTransformationResize()
    {
        $t = new VideoTransformation();

        $t->scale(100, 200);

        self::assertEquals(
            'c_scale,h_200,w_100',
            (string)$t
        );

        $t->genericResize('vasi', 100, 200);

        self::assertEquals(
            'c_scale,h_200,w_100/c_vasi,h_200,w_100',
            (string)$t
        );
    }

    public function testVideoTransformationLayers()
    {
        $this->assertEquals(
            'l_subtitles:sample_sub_en.srt/fl_layer_apply',
            (string)(new VideoTransformation())->addSubtitles('sample_sub_en.srt')
        );

        $this->assertEquals(
            'l_subtitles:sample_sub_en.srt/fl_layer_apply',
            (string)(new VideoTransformation())->overlay(new SubtitlesLayer('sample_sub_en.srt'))
        );

        $this->assertEquals(
            'l_lut:iwltbap_aspen.3dl/fl_layer_apply',
            (string)(new VideoTransformation())->add3DLut('iwltbap_aspen.3dl')
        );
    }

    public function testVideoTransformationEffects()
    {
        $this->assertEquals(
            'e_noise:17/e_fade:2000/e_fade:-2000/e_deshake:17',
            (string)(new VideoTransformation())
                ->effect(VideoEffect::noise(17))
                ->effect(Effect::fadeIn(2000))
                ->effect(Effect::fadeOut(2000))
                ->effect((Effect::deshake(17)))
        );
    }

    public function testVideoTransformationFps()
    {
        $this->assertEquals(
            'fps_25',
            (string)(new VideoTransformation())
                ->fps(25)
        );

        $this->assertEquals(
            'fps_25-30',
            (string)(new VideoTransformation())
                ->fps(25, 30)
        );

        $this->assertEquals(
            'fps_25-',
            (string)(new VideoTransformation())
                ->fps('25-')
        );

        $this->assertEquals(
            'fps_25',
            (string)(new VideoTransformation())
                ->fps(new Fps(25))
        );
    }

    public function testVideoTransformationKeyframeInterval()
    {
        $this->assertEquals(
            'ki_0.15',
            (string)(new VideoTransformation())
                ->keyframeInterval(0.15)
        );
    }

    public function testVideoTransformationStreamingProfile()
    {
        $this->assertEquals(
            'sp_full_hd',
            (string)(new VideoTransformation())
                ->streamingProfile(StreamingProfile::FULL_HD)
        );
    }

    public function testVideoTransformationBitRate()
    {
        $this->assertEquals(
            'br_120000',
            (string)(new VideoTransformation())
                ->bitRate(120000)
        );

        $this->assertEquals(
            'br_250k',
            (string)(new VideoTransformation())
                ->bitRate('250k')
        );

        $this->assertEquals(
            'br_2m',
            (string)(new VideoTransformation())
                ->bitRate('2m')
        );
    }

    public function testVideoTransformationVariousParams()
    {
        $this->assertStrEquals(
            'vs_2',
            (new VideoTransformation())
                ->videoSampling(2)
        );
    }

    public function testVideoTransformationFlags()
    {
        $this->assertEquals(
            'fl_streaming_attachment:my_streaming_video.mp4',
            (string)(new VideoTransformation())
                ->streamingAttachment('my_streaming_video.mp4')
        );

        $this->assertEquals(
            'fl_hlsv3',
            (string)(new Transformation())
                ->hlsv3()
        );
    }
}
