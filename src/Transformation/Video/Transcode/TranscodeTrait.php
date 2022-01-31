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
 * Trait TranscodeTrait
 *
 * @api
 */
trait TranscodeTrait
{
    /**
     * Controls the video codec.
     *
     * @param VideoCodec|string $codec The video codec.
     *
     * @return BitRate
     *
     * @see \Cloudinary\Transformation\BitRate
     */
    public static function videoCodec($codec)
    {
        return ClassUtils::verifyInstance($codec, VideoCodec::class);
    }

    /**
     * Converts a video to animated image.
     *
     * @param $animatedImageFormat
     *
     * @return ToAnimatedAction
     *
     * @see \Cloudinary\Transformation\BitRate
     */
    public static function toAnimated($animatedImageFormat = null)
    {
        return ClassUtils::forceInstance($animatedImageFormat, ToAnimatedAction::class);
    }
}
