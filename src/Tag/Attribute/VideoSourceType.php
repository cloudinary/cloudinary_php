<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Tag;

/**
 * Class VideoSourceType
 *
 * 'type' attribute of the video source tag
 *
 * @internal
 */
class VideoSourceType extends SourceType
{
    public const MP4  = 'mp4';
    public const WEBM = 'webm';
    public const OGG  = 'ogg';

    /**
     * VideoSourceType constructor.
     *
     * @param ?string            $type   The type of the video source.
     * @param array|string|null $codecs The codecs.
     */
    public function __construct(?string $type = null, array|string|null $codecs = null)
    {
        parent::__construct(self::MEDIA_TYPE_VIDEO, $type, $codecs);
    }

    /**
     * The mp4 video source type.
     *
     * @param array|string|null $codecs The codecs.
     *
     */
    public static function mp4(array|string|null $codecs = null): VideoSourceType
    {
        return new VideoSourceType(self::MP4, $codecs);
    }

    /**
     * The webm video source type.
     *
     * @param array|string|null $codecs The codecs.
     *
     */
    public static function webm(array|string|null $codecs = null): VideoSourceType
    {
        return new VideoSourceType(self::WEBM, $codecs);
    }

    /**
     * The ogg video source type.
     *
     * @param array|string|null $codecs The codecs.
     *
     */
    public static function ogg(array|string|null $codecs = null): VideoSourceType
    {
        return new VideoSourceType(self::OGG, $codecs);
    }
}
