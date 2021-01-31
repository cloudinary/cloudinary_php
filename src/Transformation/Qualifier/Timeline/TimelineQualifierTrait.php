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
 * Trait TimelineQualifierTrait
 *
 * @api
 */
trait TimelineQualifierTrait
{
    /**
     * Sets the starting position of the part of the video to keep when trimming videos.
     *
     * @param mixed $startOffset The starting position of the part of the video to keep. This can be specified as a
     *                           float representing the time in seconds or a string representing the percentage of the
     *                           video length (for example, "30%" or "30p").
     *
     * @return StartOffset
     */
    public static function startOffset($startOffset)
    {
        return ClassUtils::verifyInstance($startOffset, StartOffset::class);
    }

    /**
     * Sets the end position of the part of the video to keep when trimming videos.
     *
     * @param mixed $endOffset The end position of the part of the video to keep. This can be specified as a
     *                         float representing the time in seconds or a string representing the percentage of the
     *                         video length (for example, "30%" or "30p").
     *
     * @return EndOffset
     */
    public static function endOffset($endOffset)
    {
        return ClassUtils::verifyInstance($endOffset, EndOffset::class);
    }

    /**
     * Sets the duration of the video to keep.
     *
     * @param mixed $duration The length of the part of the video to keep. This can be specified as a float
     *                        representing the time in seconds or a string representing the percentage of the
     *                        video length (for example, "30%" or "30p").
     *
     * @return Duration
     */
    public static function duration($duration)
    {
        return ClassUtils::verifyInstance($duration, Duration::class);
    }
}
