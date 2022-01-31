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
     * @param int $rate  The percentage change of speed. Positive numbers speed up the playback, negative numbers
     *                   slow down the playback (Range: -50 to 100, Server default: 0).
     *
     * @return Accelerate
     */
    public static function accelerate($rate = null)
    {
        return new Accelerate($rate);
    }

    /**
     * Causes a video clip to play forwards and then backwards.
     *
     * Use in conjunction with trimming qualifiers ('duration', 'start_offset', or 'end_offset') and the 'loop' effect
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
     * Delivers a video that contains additional loops of the video.
     *
     * The total number of iterations is the number of additional loops plus one.
     *
     * For animated images (GIF), see Animated::edit()->loop().
     *
     * @param int $additionalIterations The additional number of times to play the video.
     *
     * @return Loop
     */
    public static function loop($additionalIterations = null)
    {
        return new Loop($additionalIterations);
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
        return EffectAction::fromEffectQualifier(ClassUtils::verifyInstance($level, Volume::class));
    }

    /**
     * Indicates that the video overlay is to be used as a transition between the base and second video.
     *
     * @return EffectAction
     */
    public static function transition()
    {
        return  EffectAction::named(PlaybackEffect::TRANSITION);
    }
}
