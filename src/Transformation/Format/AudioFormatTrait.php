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

/**
 * Trait AudioFormatTrait
 *
 * @api
 */
trait AudioFormatTrait
{
    /**
     * Audio format mp3.
     *
     * @return static
     */
    public static function mp3()
    {
        return static::createFormat(Format::MP3);
    }

    /**
     * Audio format aac.
     *
     * @return static
     */
    public static function aac()
    {
        return static::createFormat(Format::AAC);
    }

    /**
     * Audio format m4a.
     *
     * @return static
     */
    public static function m4a()
    {
        return static::createFormat(Format::M4A);
    }

    /**
     * Audio format ogg.
     *
     * @return static
     */
    public static function ogg()
    {
        return static::createFormat(Format::OGG);
    }

    /**
     * Audio format wav.
     *
     * @return static
     */
    public static function wav()
    {
        return static::createFormat(Format::WAV);
    }

    /**
     * Audio format aiff.
     *
     * @return static
     */
    public static function aiff()
    {
        return static::createFormat(Format::AIFF);
    }

    /**
     * Audio format flac.
     *
     * @return static
     */
    public static function flac()
    {
        return static::createFormat(Format::FLAC);
    }

    /**
     * Audio format amr.
     *
     * @return static
     */
    public static function amr()
    {
        return static::createFormat(Format::AMR);
    }

    /**
     * Audio format midi.
     *
     * @return static
     */
    public static function midi()
    {
        return static::createFormat(Format::MIDI);
    }
}
