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
    public static function videoMp4()
    {
        return static::createFormat(Format::MP4);
    }

    /**
     * Video format ts.
     *
     * @return static
     */
    public static function videoTs()
    {
        return static::createFormat(Format::TS);
    }

    /**
     * Video format mov.
     *
     * @return static
     */
    public static function videoMov()
    {
        return static::createFormat(Format::MOV);
    }

    /**
     * Video format flv.
     *
     * @return static
     */
    public static function videoFlv()
    {
        return static::createFormat(Format::FLV);
    }

    /**
     * Video format webm.
     *
     * @return static
     */
    public static function videoWebm()
    {
        return static::createFormat(Format::WEBM);
    }

    /**
     * Video format ogv.
     *
     * @return static
     */
    public static function videoOgv()
    {
        return static::createFormat(Format::OGV);
    }

    /**
     * Video format m3u8.
     *
     * @return static
     */
    public static function videoM3u8()
    {
        return static::createFormat(Format::M3U8);
    }

    /**
     * Video format mpd.
     *
     * @return static
     */
    public static function videoMpd()
    {
        return static::createFormat(Format::MPD);
    }

    /**
     * Video format mkv.
     *
     * @return static
     */
    public static function videoMkv()
    {
        return static::createFormat(Format::MKV);
    }

    /**
     * Video format avi.
     *
     * @return static
     */
    public static function videoAvi()
    {
        return static::createFormat(Format::AVI);
    }

    /**
     * Video format 3gp.
     *
     * @return static
     */
    public static function video3gp()
    {
        return static::createFormat(Format::F_3GP);
    }

    /**
     * Video format 3g2.
     *
     * @return static
     */
    public static function video3g2()
    {
        return static::createFormat(Format::F_3G2);
    }

    /**
     * Video format wmv.
     *
     * @return static
     */
    public static function videoWmv()
    {
        return static::createFormat(Format::WMV);
    }

    /**
     * Video format mpeg.
     *
     * @return static
     */
    public static function videoMpeg()
    {
        return static::createFormat(Format::MPEG);
    }
}
