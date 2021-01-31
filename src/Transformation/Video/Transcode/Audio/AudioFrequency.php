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

use Cloudinary\Transformation\Qualifier\BaseQualifier;

/**
 * Controls audio sample frequency.
 *
 * **Learn more**: <a href="https://cloudinary.com/documentation/audio_transformations#audio_frequency_control"
 * target="_blank">Audio frequency control</a>
 *
 *
 * @api
 */
class AudioFrequency extends BaseQualifier
{
    const FREQ8000   = 8000;
    const FREQ11025  = 11025;
    const FREQ16000  = 16000;
    const FREQ22050  = 22050;
    const FREQ32000  = 32000;
    const FREQ37800  = 37800;
    const FREQ44056  = 44056;
    const FREQ44100  = 44100;
    const FREQ47250  = 47250;
    const FREQ48000  = 48000;
    const FREQ88200  = 88200;
    const FREQ96000  = 96000;
    const FREQ176400 = 176400;
    const FREQ192000 = 192000;

    /*
     * Initial audio frequency
     */
    const IAF = 'iaf';

    /**
     * Audio Frequency 8000.
     *
     * @return AudioFrequency
     */
    public static function freq8000()
    {
        return self::createAudioFrequency(self::FREQ8000);
    }

    /**
     * Audio Frequency 11025.
     *
     * @return AudioFrequency
     */
    public static function freq11025()
    {
        return self::createAudioFrequency(self::FREQ11025);
    }

    /**
     * Audio Frequency 16000.
     *
     * @return AudioFrequency
     */
    public static function freq16000()
    {
        return self::createAudioFrequency(self::FREQ16000);
    }

    /**
     * Audio Frequency 22050.
     *
     * @return AudioFrequency
     */
    public static function freq22050()
    {
        return self::createAudioFrequency(self::FREQ22050);
    }

    /**
     * Audio Frequency 32000.
     *
     * @return AudioFrequency
     */
    public static function freq32000()
    {
        return self::createAudioFrequency(self::FREQ32000);
    }

    /**
     * Audio Frequency 37800.
     *
     * @return AudioFrequency
     */
    public static function freq37800()
    {
        return self::createAudioFrequency(self::FREQ37800);
    }

    /**
     * Audio Frequency 44056.
     *
     * @return AudioFrequency
     */
    public static function freq44056()
    {
        return self::createAudioFrequency(self::FREQ44056);
    }

    /**
     * Audio Frequency 44100.
     *
     * @return AudioFrequency
     */
    public static function freq44100()
    {
        return self::createAudioFrequency(self::FREQ44100);
    }

    /**
     * Audio Frequency 47250.
     *
     * @return AudioFrequency
     */
    public static function freq47250()
    {
        return self::createAudioFrequency(self::FREQ47250);
    }

    /**
     * Audio Frequency 48000.
     *
     * @return AudioFrequency
     */
    public static function freq48000()
    {
        return self::createAudioFrequency(self::FREQ48000);
    }

    /**
     * Audio Frequency 88200.
     *
     * @return AudioFrequency
     */
    public static function freq88200()
    {
        return self::createAudioFrequency(self::FREQ88200);
    }

    /**
     * Audio Frequency 96000.
     *
     * @return AudioFrequency
     */
    public static function freq96000()
    {
        return self::createAudioFrequency(self::FREQ96000);
    }

    /**
     * Audio Frequency 176400.
     *
     * @return AudioFrequency
     */
    public static function freq176400()
    {
        return self::createAudioFrequency(self::FREQ176400);
    }

    /**
     * Audio Frequency 192000.
     *
     * @return AudioFrequency
     */
    public static function freq192000()
    {
        return self::createAudioFrequency(self::FREQ192000);
    }

    /**
     * Retain the original audio frequency of the video.
     * This applies when using vc_auto where the audio frequency defaults to 48kHz.
     *
     * @return AudioFrequency
     */
    public static function iaf()
    {
        return self::createAudioFrequency(self::IAF);
    }

    /**
     * Creates a new instance of the AudioFrequency class.
     *
     * @param int|string $qualifier The audio frequency.
     *
     * @return AudioFrequency
     */
    protected static function createAudioFrequency($qualifier)
    {
        return new self($qualifier);
    }
}
