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
use Cloudinary\Transformation\Adjust;
use Cloudinary\Transformation\Argument\Color;
use Cloudinary\Transformation\Argument\Range\Range;
use Cloudinary\Transformation\AspectRatio;
use Cloudinary\Transformation\Compass;
use Cloudinary\Transformation\Effect;
use Cloudinary\Transformation\Fps;
use Cloudinary\Transformation\Gravity;
use Cloudinary\Transformation\ImageSource;
use Cloudinary\Transformation\Overlay;
use Cloudinary\Transformation\Pad;
use Cloudinary\Transformation\Position;
use Cloudinary\Transformation\Qualifier\Dimensions\Width;
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\Scale;
use Cloudinary\Transformation\Source;
use Cloudinary\Transformation\StreamingProfile;
use Cloudinary\Transformation\SubtitlesSource;
use Cloudinary\Transformation\TextStyle;
use Cloudinary\Transformation\Timeline;
use Cloudinary\Transformation\Transcode;
use Cloudinary\Transformation\Transformation;
use Cloudinary\Transformation\VideoEdit;
use Cloudinary\Transformation\VideoEffect;
use Cloudinary\Transformation\VideoSource;
use Cloudinary\Transformation\VideoTransformation;
use UnexpectedValueException;

/**
 * Class VideoTransformationTest
 */
final class VideoTransformationTest extends UnitTestCase
{
    public function testVideoTransformation()
    {
        $t = new VideoTransformation();

        $t->resize(Scale::scale(new Width(100), 200)->aspectRatio(AspectRatio::ignoreInitialAspectRatio()))->resize(
            Pad::limitPad(50)
        )
          ->rotate(17);

        $t_expected = 'c_scale,fl_ignore_aspect_ratio,h_200,w_100/c_lpad,w_50/a_17';
        self::assertEquals(
            $t_expected,
            (string)$t
        );

        $t->trim(Timeline::position('auto', '90%', 10.1));

        $t2_expected = $t_expected . '/du_10.1,eo_90p,so_auto';
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

    public function testVideoTransformationVideoEdit()
    {
        $t = new VideoTransformation();

        $t->videoEdit(VideoEdit::trim()->startOffset('auto')->endOffset('90%')->duration(10.1));

        self::assertEquals(
            'du_10.1,eo_90p,so_auto',
            (string)$t
        );
    }

    public function testPreview()
    {
        self::assertEquals(
            'e_preview:duration_17',
            (string)VideoEdit::preview(17)
        );

        self::assertEquals(
            'e_preview:duration_17:max_seg_3:min_seg_dur_3',
            (string)VideoEdit::preview()->duration(17)->maximumSegments(3)->minimumSegmentDuration(3)
        );
    }

    public function testVideoTransformationLayers()
    {
        self::assertEquals(
            'l_subtitles:sample_sub_en.srt/fl_layer_apply',
            (string)(new VideoTransformation())->addSubtitles('sample_sub_en.srt')
        );

        self::assertEquals(
            'l_subtitles:sample_sub_en.srt/fl_layer_apply',
            (string)(new VideoTransformation())->overlay(new SubtitlesSource('sample_sub_en.srt'))
        );

        self::assertEquals(
            'l_logo/c_thumb,h_50,w_50/o_30/du_5,fl_layer_apply,g_north_west,x_10,y_10',
            (string)(new VideoTransformation())->overlay(
                Overlay::videoSource(
                    VideoSource::image('logo')->resize(Resize::thumbnail(50, 50))->adjust(Adjust::opacity(30))
                )->position((new Position())
                    ->gravity(Gravity::compass(Compass::northWest()))
                    ->offsetX(10)->offsetY(10))
                ->timeline(Timeline::position()->duration(5))
            )
        );

        self::assertEquals(
            'l_text:Arial_30:Earth/e_outline:5/fl_layer_apply,g_north_west,so_5,x_10,y_10',
            (string)(new VideoTransformation())->overlay(
                Overlay::source(
                    Source::text('Earth')->fontFamily('Arial')->fontSize(30)->effect(Effect::outline(5))
                )->position(
                    (new Position())->gravity(Gravity::compass(Compass::northWest()))->offsetX(10)->offsetY(10)
                )->timeline(Timeline::position(5))
            )
        );

        self::assertEquals(
            'l_lut:iwltbap_aspen.3dl/fl_layer_apply',
            (string)(new VideoTransformation())->adjust(Adjust::by3dLut('iwltbap_aspen.3dl'))
        );
    }

    public function testVideoTransformationEffects()
    {
        self::assertEquals(
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
        self::assertEquals(
            'fps_25',
            (string)(new VideoTransformation())
                ->fps(25)
        );

        self::assertEquals(
            'fps_25-30',
            (string)(new VideoTransformation())
                ->transcode(Transcode::fpsRange(25, 30))
        );

        self::assertEquals(
            'fps_25-',
            (string)(new VideoTransformation())
                ->transcode(Transcode::fps('25-'))
        );

        self::assertEquals(
            'fps_25',
            (string)(new VideoTransformation())
                ->fps(new Fps(25))
        );
    }

    public function testVideoTransformationKeyframeInterval()
    {
        self::assertEquals(
            'ki_0.15',
            (string)(new VideoTransformation())
                ->keyframeInterval(0.15)
        );
    }

    public function testVideoTransformationStreamingProfile()
    {
        self::assertEquals(
            'sp_full_hd',
            (string)(new VideoTransformation())
                ->streamingProfile(StreamingProfile::fullHd())
        );
    }

    public function testVideoTransformationBitRate()
    {
        self::assertEquals(
            'br_120000',
            (string)(new VideoTransformation())
                ->bitRate(120000)
        );

        self::assertEquals(
            'br_250k',
            (string)(new VideoTransformation())
                ->bitRate('250k')
        );

        self::assertEquals(
            'br_2m',
            (string)(new VideoTransformation())
                ->bitRate('2m')
        );
    }

    public function testVideoTransformationVariousParams()
    {
        self::assertStrEquals(
            'vs_2',
            (new VideoTransformation())
                ->videoSampling(2)
        );
    }

    public function testVideoTransformationFlags()
    {
        self::assertEquals(
            'fl_streaming_attachment:my_streaming_video%252Emp4',
            (string)(new VideoTransformation())
                ->streamingAttachment('my_streaming_video.mp4')
        );

        self::assertEquals(
            'fl_hlsv3',
            (string)(new Transformation())
                ->hlsv3()
        );
    }

    public function testVideoTransformationRange()
    {
        self::assertEquals(
            '1.2,3.4',
            (string)(new Range([1.2, 3.4]))
        );
    }

    public function testExceptionsVideoTransformationRange()
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('A valid Range is expected');

        new Range([1.2, 3.4, 3.4]);
    }
}
