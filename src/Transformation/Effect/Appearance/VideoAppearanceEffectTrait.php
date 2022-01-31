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
 * Trait VideoAppearanceEffectTrait
 *
 * @api
 */
trait VideoAppearanceEffectTrait
{
    /**
     * Removes small motion shifts from the video. with a maximum extent of movement in the horizontal and vertical
     * direction of 32 pixels
     *
     * @param int $shakeStrength The maximum number of pixels in the horizontal and vertical direction that will be
     *                           addressed. (Possible values: 16, 32, 48, 64. Server default: 16)
     *
     * @return Deshake
     */
    public static function deshake($shakeStrength = null)
    {
        return ClassUtils::forceInstance($shakeStrength, Deshake::class);
    }

    /**
     * Fade in at the beginning of the video.
     *
     * For details and examples, see 'Fade in and out' in the Video Transformations guide.
     *
     * @param int $duration The time in ms for the fade to occur. (Server default: 1000)
     *
     * @return DurationEffectAction
     *
     * @see https://cloudinary.com/documentation/video_manipulation_and_delivery#fade_in_and_out
     */
    public static function fadeIn($duration = null)
    {
        return EffectAction::withDuration(AppearanceEffect::FADE, $duration);
    }

    /**
     *  Fade out at the end of the video.
     *
     * For details and examples, see 'Fade in and out' in the Video Transformations guide.
     *
     * @param int $duration The time in ms for the fade to occur.
     *
     * @return DurationEffectAction
     *
     * @see https://cloudinary.com/documentation/video_manipulation_and_delivery#fade_in_and_out
     */
    public static function fadeOut($duration = null)
    {
        return EffectAction::withDuration(AppearanceEffect::FADE, $duration, true);
    }

    /**
     * Adds visual noise to the video, visible as a random flicker of "dots" or "snow".
     *
     * @param int $percentage The percent of noise to apply. (Range: 0 to 100 Server default: 0)
     *
     * @return EffectAction
     */
    public static function noise($percentage = null)
    {
        return EffectAction::withLevel(AppearanceEffect::NOISE, EffectRange::PERCENT, $percentage);
    }
}
