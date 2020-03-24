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
 * Trait ImageLayerTrait
 *
 * @api
 */
trait VideoLayerTrait
{
    /**
     * Adds another video layer.
     *
     * @param string $videoId The public ID of the new video layer.
     *
     * @return static|VideoLayer
     */
    public static function video($videoId = null)
    {
        return static::createWithLayer(ClassUtils::verifyInstance($videoId, VideoLayer::class));
    }

    /**
     * Adds subtitles to a video.
     *
     * @param string $subtitlesId The public ID of the subtitles file.
     *
     * @return static|SubtitlesLayer
     */
    public static function subtitles($subtitlesId = null)
    {
        return static::createWithLayer(ClassUtils::verifyInstance($subtitlesId, SubtitlesLayer::class));
    }
}
