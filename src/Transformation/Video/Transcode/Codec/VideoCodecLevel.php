<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Codec;

/**
 * Defines the video codec level.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/video_manipulation_and_delivery#video_codec_settings" target="_blank">
 * Video codec settings</a>
 *
 * @api
 */
class VideoCodecLevel
{
    const VCL_30 = '3.0';
    const VCL_31 = '3.1';
    const VCL_40 = '4.0';
    const VCL_41 = '4.1';
    const VCL_42 = '4.2';
    const VCL_50 = '5.0';
    const VCL_51 = '5.1';
    const VCL_52 = '5.2';

    /**
     * Video codec level 3.0.
     *
     * @return string
     */
    public static function vcl30()
    {
        return self::VCL_30;
    }

    /**
     * Video codec level 3.1.
     *
     * @return string
     */
    public static function vcl31()
    {
        return self::VCL_31;
    }

    /**
     * Video codec level 4.0.
     *
     * @return string
     */
    public static function vcl40()
    {
        return self::VCL_40;
    }

    /**
     * Video codec level 4.1.
     *
     * @return string
     */
    public static function vcl41()
    {
        return self::VCL_41;
    }

    /**
     * Video codec level 4.2.
     *
     * @return string
     */
    public static function vcl42()
    {
        return self::VCL_42;
    }

    /**
     * Video codec level 5.0.
     *
     * @return string
     */
    public static function vcl50()
    {
        return self::VCL_50;
    }

    /**
     * Video codec level 5.1.
     *
     * @return string
     */
    public static function vcl51()
    {
        return self::VCL_51;
    }

    /**
     * Video codec level 5.2.
     *
     * @return string
     */
    public static function vcl52()
    {
        return self::VCL_52;
    }
}
