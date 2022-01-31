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
    const WAV  = 'wav';
    const MP3  = 'mpeg';
    const MP4  = 'mp4';
    const AAC  = 'aac';
    const WEBM = 'webm';
    const FLAC = 'flac';

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
