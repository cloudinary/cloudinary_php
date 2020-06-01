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
    const MP4  = 'mp4';
    const WEBM = 'webm';
    const OGG  = 'ogg';

    /**
     * VideoSourceType constructor.
     *
     * @param string $type   The type of the video source.
     * @param null   $codecs The codecs.
     */
    public function __construct($type = null, $codecs = null)
    {
        parent::__construct(self::MEDIA_TYPE_VIDEO, $type, $codecs);
    }

    /**
     * The mp4 video source type.
     *
     * @param string|array $codecs The codecs.
     *
     * @return VideoSourceType
     */
    public static function mp4($codecs = null)
    {
        return new VideoSourceType(self::MP4, $codecs);
    }

    /**
     * The webm video source type.
     *
     * @param string|array $codecs The codecs.
     *
     * @return VideoSourceType
     */
    public static function webm($codecs = null)
    {
        return new VideoSourceType(self::WEBM, $codecs);
    }

    /**
     * The ogg video source type.
     *
     * @param string|array $codecs The codecs.
     *
     * @return VideoSourceType
     */
    public static function ogg($codecs = null)
    {
        return new VideoSourceType(self::OGG, $codecs);
    }
}
