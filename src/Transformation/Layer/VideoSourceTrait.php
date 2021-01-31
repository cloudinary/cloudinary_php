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
 * Trait VideoSourceTrait
 *
 * @api
 */
trait VideoSourceTrait
{
    /**
     * Adds another video layer.
     *
     * @param string $videoId The public ID of the new video layer.
     *
     * @return static|VideoSource
     */
    public static function video($videoId = null)
    {
        return static::createWithSource(ClassUtils::verifyInstance($videoId, VideoSource::class));
    }

    /**
     * Adds subtitles to a video.
     *
     * @param string $subtitlesId The public ID of the subtitles file.
     *
     * @return static|SubtitlesSource
     */
    public static function subtitles($subtitlesId = null)
    {
        return static::createWithSource(ClassUtils::verifyInstance($subtitlesId, SubtitlesSource::class));
    }
}
