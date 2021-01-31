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
 * Trait VideoEditBuilderTrait
 *
 * @api
 */
trait VideoEditBuilderTrait
{
    /**
     * Trims a video (and discards the rest).
     *
     * @param null $startOffset
     * @param null $endOffset
     * @param null $duration
     *
     * @return Timeline
     *
     * @see https://cloudinary.com/documentation/video_manipulation_and_delivery#trimming_videos
     */
    public static function trim($startOffset = null, $endOffset = null, $duration = null)
    {
        return ClassUtils::forceVarArgsInstance([$startOffset, $endOffset, $duration], Timeline::class);
    }

    /**
     * Concatenates another video or image.
     *
     * @param VideoSource|string $videoSource The source to concatenate.
     *
     * @return Concatenate
     *
     * @see https://cloudinary.com/documentation/video_manipulation_and_delivery#concatenating_videos
     */
    public static function concatenate($videoSource)
    {
        return ClassUtils::verifyInstance($videoSource, Concatenate::class);
    }

    /**
     * Generates an excerpt of the video based on Cloudinary's AI-powered preview algorithm, which identifies the
     * most interesting video chunks from a video and uses these to generate a video preview.
     *
     * @param int $duration The duration of the excerpt in seconds (Server default: 5 seconds).
     *
     * @return Preview
     */
    public static function preview($duration = null)
    {
        return ClassUtils::forceInstance($duration, Preview::class);
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
}
