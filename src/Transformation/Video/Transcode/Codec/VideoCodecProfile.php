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
 * Defines the video codec profile.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/video_manipulation_and_delivery#video_codec_settings" target="_blank">
 * Video codec settings</a>
 *
 * @api
 */
class VideoCodecProfile
{
    const BASELINE = 'baseline';
    const MAIN     = 'main';
    const HIGH     = 'high';

    /**
     * Video codec profile baseline.
     *
     * @return string
     */
    public static function baseline()
    {
        return self::BASELINE;
    }

    /**
     * Video codec profile main.
     *
     * @return string
     */
    public static function main()
    {
        return self::MAIN;
    }

    /**
     * Video codec profile high.
     *
     * @return string
     */
    public static function high()
    {
        return self::HIGH;
    }
}
