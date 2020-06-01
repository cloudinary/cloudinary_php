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
 * Trait FormatTrait
 *
 * @api
 */
trait VideoFormatTrait
{
    /**
     * Video format mp4.
     *
     * @return static
     */
    public static function mp4()
    {
        return static::createFormat(Format::MP4);
    }

    /**
     * Video format ts.
     *
     * @return static
     */
    public static function ts()
    {
        return static::createFormat(Format::TS);
    }

    /**
     * Video format mov.
     *
     * @return static
     */
    public static function mov()
    {
        return static::createFormat(Format::MOV);
    }

    /**
     * Video format flv.
     *
     * @return static
     */
    public static function flv()
    {
        return static::createFormat(Format::FLV);
    }

    /**
     * Video format webm.
     *
     * @return static
     */
    public static function webm()
    {
        return static::createFormat(Format::WEBM);
    }

    /**
     * Video format ogv.
     *
     * @return static
     */
    public static function ogv()
    {
        return static::createFormat(Format::OGV);
    }

    /**
     * Video format m3u8.
     *
     * @return static
     */
    public static function m3u8()
    {
        return static::createFormat(Format::M3U8);
    }

    /**
     * Video format mpd.
     *
     * @return static
     */
    public static function mpd()
    {
        return static::createFormat(Format::MPD);
    }

    /**
     * Video format mkv.
     *
     * @return static
     */
    public static function mkv()
    {
        return static::createFormat(Format::MKV);
    }

    /**
     * Video format avi.
     *
     * @return static
     */
    public static function avi()
    {
        return static::createFormat(Format::AVI);
    }

    /**
     * Video format 3gp.
     *
     * @return static
     */
    public static function f3gp()
    {
        return static::createFormat(Format::F_3GP);
    }

    /**
     * Video format 3g2.
     *
     * @return static
     */
    public static function f3g2()
    {
        return static::createFormat(Format::F_3G2);
    }

    /**
     * Video format wmv.
     *
     * @return static
     */
    public static function wmv()
    {
        return static::createFormat(Format::WMV);
    }

    /**
     * Video format mpeg.
     *
     * @return static
     */
    public static function mpeg()
    {
        return static::createFormat(Format::MPEG);
    }
}
