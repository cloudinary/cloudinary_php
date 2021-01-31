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

use Cloudinary\Utils;

/**
 * Controls the volume of an audio or video file.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/audio_transformations#adjust_the_audio_volume" target="_blank">
 * Adjust the audio volume</a>
 *
 * @api
 */
class Volume extends LimitedEffectQualifier
{
    const MUTE = 'mute';

    /**
     * Volume constructor.
     *
     * @param mixed $volume
     */
    public function __construct($volume = null)
    {
        parent::__construct(PlaybackEffect::VOLUME, EffectRange::AUDIO_VOLUME);

        $this->setVolume($volume);
    }

    /**
     * Named Volume constructor.
     *
     * @param mixed $volume
     *
     * @return Volume
     */
    public static function volume($volume)
    {
        return new self($volume);
    }

    /**
     * Increases or decreases the volume by the specified number of decibels.
     *
     * @param int $dBOffset The offset in dB.
     *
     * @return Volume
     */
    public static function byDecibels($dBOffset)
    {
        return new self(Utils::formatSigned($dBOffset) . 'dB');
    }

    /**
     * Increases or decreases the volume by a percentage of the current volume.
     *
     * @param int $level The percentage change of volume (Range: -100 to 400).
     *
     * @return Volume
     */
    public static function byPercent($level)
    {
        return new self($level);
    }

    /**
     * Mutes the volume.
     *
     * You can use this on the base video to deliver a video without sound, or with a video overlay
     * to ensure that only the sound from the base video plays.
     *
     * @return Volume
     */
    public static function mute()
    {
        return new self(self::MUTE);
    }

    /**
     * Increases or decreases the volume by a percentage of the current volume.
     *
     * @param int $value The percentage change of volume (Range: -100 to 400).
     *
     * @return Volume
     */
    protected function setVolume($value)
    {
        $this->value->setSimpleValue('volume', $value);

        return $this;
    }
}
