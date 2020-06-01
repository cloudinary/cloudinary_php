<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation;

use Cloudinary\ClassUtils;
use Cloudinary\Transformation\Parameter\VideoRange\VideoRange;

/**
 * Trait VideoEditBuilderTrait
 *
 * @api
 */
trait VideoEditBuilderTrait
{
    /**
     * Trims a video (and discards the rest).
     *
     * @param VideoRange|mixed $range Specify the range of the video to keep.
     *
     * @return VideoRange
     *
     * @see https://cloudinary.com/documentation/video_manipulation_and_delivery#trimming_videos
     */
    public static function trim($range = null)
    {
        return ClassUtils::verifyInstance($range, VideoRange::class);
    }

    /**
     * Concatenates another video or image.
     *
     * @param VideoLayer|string $videoSource      The source to concatenate.
     * @param VideoRange        $timelinePosition The position of the concatenated video.
     *
     * @return VideoOverlay
     *
     * @see https://cloudinary.com/documentation/video_manipulation_and_delivery#concatenating_videos
     */
    public static function concatenate($videoSource, $timelinePosition = null)
    {
        return (new VideoOverlay($videoSource, null, $timelinePosition))->concatenate();
    }

    /**
     * Increases or decreases the volume by a percentage of the current volume.
     *
     * Also see \Cloudinary\Transformation\Volume for different ways to change the volume.
     *
     * @param int|Volume $level The percentage change of volume (Range: -100 to 400).
     *
     * @return EffectAction
     *
     * @see \Cloudinary\Transformation\Volume
     */
    public static function volume($level)
    {
        return EffectAction::fromEffectParam(ClassUtils::verifyInstance($level, Volume::class));
    }
}
