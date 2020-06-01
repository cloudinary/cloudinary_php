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
use Cloudinary\Transformation\Argument\Range\Duration;

/**
 * Trait PlaybackEffectTrait
 *
 * @api
 */
trait PlaybackEffectTrait
{
    /**
     * Changes the speed of the video playback.
     *
     * @param int $speed The percentage change of speed. Positive numbers speed up the playback, negative numbers
     *                   slow down the playback (Range: -50 to 100, Server default: 0).
     *
     * @return EffectAction
     */
    public static function accelerate($speed)
    {
        return EffectAction::limited(PlaybackEffect::ACCELERATE, EffectRange::EXTENDED_PERCENT, $speed);
    }

    /**
     * Causes a video clip to play forwards and then backwards.
     *
     * Use in conjunction with trimming parameters ('duration', 'start_offset', or 'end_offset') and the 'loop' effect
     * to deliver a classic (short, repeating) boomerang clip.
     * For details and examples, see 'Create a boomerang video clip' in the Video Transformations guide.
     *
     * @return EffectAction
     *
     * @see https://cloudinary.com/documentation/video_manipulation_and_delivery#create_a_boomerang_video_clip
     */
    public static function boomerang()
    {
        return EffectAction::named(PlaybackEffect::BOOMERANG);
    }

    /**
     * Delivers a video or animated GIF that contains additional loops of the video/GIF.
     *
     * The total number of iterations is the number of additional loops plus one.
     * For animated GIFs only, you can also specify the loop effect without a numeric value to instruct it to loop
     * the GIF infinitely.
     *
     * @param int $additionalLoops The additional number of times to play the video or animated GIF.
     *
     * @return EffectAction
     */
    public static function loop($additionalLoops = null)
    {
        return EffectAction::valued(PlaybackEffect::LOOP, $additionalLoops);
    }

    /**
     * Generates an excerpt of the video based on Cloudinary's AI-powered preview algorithm, which identifies the
     * most interesting video chunks from a video and uses these to generate a video preview.
     *
     * @param int $duration The duration of the excerpt in seconds (Server default: 5 seconds).
     *
     * @return EffectAction
     */
    public static function preview($duration = null)
    {
        return EffectAction::valued(PlaybackEffect::PREVIEW, ClassUtils::verifyInstance($duration, Duration::class));
    }

    /**
     * Plays the video or audio file in reverse.
     *
     * @return EffectAction
     */
    public static function reverse()
    {
        return EffectAction::named(PlaybackEffect::REVERSE);
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
