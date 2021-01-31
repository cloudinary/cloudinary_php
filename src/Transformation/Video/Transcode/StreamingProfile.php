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
 * The predefined streaming profiles.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/video_manipulation_and_delivery#predefined_streaming_profiles"
 * target="_blank">Predefined streaming profiles</a>
 *
 * @api
 */
class StreamingProfile extends BaseQualifier
{
    const SP_4K        = '4k';
    const FULL_HD      = 'full_hd';
    const HD           = 'hd';
    const SD           = 'sd';
    const FULL_HD_WIFI = 'full_hd_wifi';
    const FULL_HD_LEAN = 'full_hd_lean';
    const HD_LEAN      = 'hd_lean';

    /**
     * Streaming profile 4k.
     *
     * @return string
     */
    public static function sp4k()
    {
        return self::SP_4K;
    }

    /**
     * Streaming profile full hd.
     *
     * @return string
     */
    public static function fullHd()
    {
        return self::FULL_HD;
    }

    /**
     * Streaming profile hd.
     *
     * @return string
     */
    public static function hd()
    {
        return self::HD;
    }

    /**
     * Streaming profile sd.
     *
     * @return string
     */
    public static function sd()
    {
        return self::SD;
    }

    /**
     * Streaming profile full hd wifi.
     *
     * @return string
     */
    public static function fullHdWifi()
    {
        return self::FULL_HD_WIFI;
    }

    /**
     * Streaming profile full hd lean.
     *
     * @return string
     */
    public static function fullHdLean()
    {
        return self::FULL_HD_LEAN;
    }

    /**
     * Streaming profile hd lean.
     *
     * @return string
     */
    public static function hdLean()
    {
        return self::HD_LEAN;
    }
}
