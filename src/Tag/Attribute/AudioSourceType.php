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
 * Class AudioSourceType
 *
 * 'type' attribute of the audio source tag
 *
 * @internal
 */
class AudioSourceType extends SourceType
{
    public const WAV = 'wav';
    public const MP3 = 'mpeg';
    public const MP4 = 'mp4';
    public const AAC = 'aac';
    public const WEBM = 'webm';
    public const FLAC = 'flac';

    /**
     * AudioSourceType constructor.
     *
     * @param string $type   The type of the audio source.
     * @param null   $codecs The codecs.
     */
    public function __construct($type = null, $codecs = null)
    {
        parent::__construct(self::MEDIA_TYPE_AUDIO, $type, $codecs);
    }
}
