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

use Cloudinary\Transformation\Parameter\BaseParameter;

/**
 * Class AudioFrequency
 */
class AudioFrequency extends BaseParameter
{
    const AF8000   = 8000;
    const AF11025  = 11025;
    const AF16000  = 16000;
    const AF22050  = 22050;
    const AF32000  = 32000;
    const AF37800  = 37800;
    const AF44056  = 44056;
    const AF44100  = 44100;
    const AF47250  = 47250;
    const AF48000  = 48000;
    const AF88200  = 88200;
    const AF96000  = 96000;
    const AF176400 = 176400;
    const AF192000 = 192000;

    /*
     * Initial audio frequency
     */
    const IAF = 'iaf';

    /**
     * Audio Frequency 8000.
     *
     * @return AudioFrequency
     */
    public static function af8000()
    {
        return self::createAudioFrequency(self::AF8000);
    }

    /**
     * Audio Frequency 11025.
     *
     * @return AudioFrequency
     */
    public static function af11025()
    {
        return self::createAudioFrequency(self::AF11025);
    }

    /**
     * Audio Frequency 16000.
     *
     * @return AudioFrequency
     */
    public static function af16000()
    {
        return self::createAudioFrequency(self::AF16000);
    }

    /**
     * Audio Frequency 22050.
     *
     * @return AudioFrequency
     */
    public static function af22050()
    {
        return self::createAudioFrequency(self::AF22050);
    }

    /**
     * Audio Frequency 32000.
     *
     * @return AudioFrequency
     */
    public static function af32000()
    {
        return self::createAudioFrequency(self::AF32000);
    }

    /**
     * Audio Frequency 37800.
     *
     * @return AudioFrequency
     */
    public static function af37800()
    {
        return self::createAudioFrequency(self::AF37800);
    }

    /**
     * Audio Frequency 44056.
     *
     * @return AudioFrequency
     */
    public static function af44056()
    {
        return self::createAudioFrequency(self::AF44056);
    }

    /**
     * Audio Frequency 44100.
     *
     * @return AudioFrequency
     */
    public static function af44100()
    {
        return self::createAudioFrequency(self::AF44100);
    }

    /**
     * Audio Frequency 47250.
     *
     * @return AudioFrequency
     */
    public static function af47250()
    {
        return self::createAudioFrequency(self::AF47250);
    }

    /**
     * Audio Frequency 48000.
     *
     * @return AudioFrequency
     */
    public static function af48000()
    {
        return self::createAudioFrequency(self::AF48000);
    }

    /**
     * Audio Frequency 88200.
     *
     * @return AudioFrequency
     */
    public static function af88200()
    {
        return self::createAudioFrequency(self::AF88200);
    }

    /**
     * Audio Frequency 96000.
     *
     * @return AudioFrequency
     */
    public static function af96000()
    {
        return self::createAudioFrequency(self::AF96000);
    }

    /**
     * Audio Frequency 176400.
     *
     * @return AudioFrequency
     */
    public static function af176400()
    {
        return self::createAudioFrequency(self::AF176400);
    }

    /**
     * Audio Frequency 192000.
     *
     * @return AudioFrequency
     */
    public static function af192000()
    {
        return self::createAudioFrequency(self::AF192000);
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
     * @param int|string $param The audio frequency.
     *
     * @return AudioFrequency
     */
    protected static function createAudioFrequency($param)
    {
        return new self($param);
    }
}
