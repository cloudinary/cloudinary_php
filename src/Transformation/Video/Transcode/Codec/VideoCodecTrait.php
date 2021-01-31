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
 * Trait VideoCodecTrait
 *
 * @api
 */
trait VideoCodecTrait
{
    /**
     * Auto video codec.
     *
     * @return VideoCodec
     */
    public static function auto()
    {
        return new VideoCodec(VideoCodec::AUTO);
    }

    /**
     * Video codec vp8.
     *
     * @return VideoCodec
     */
    public static function vp8()
    {
        return new VideoCodec(VideoCodec::VP8);
    }

    /**
     * Video codec vp9.
     *
     * @return VideoCodec
     */
    public static function vp9()
    {
        return new VideoCodec(VideoCodec::VP9);
    }

    /**
     * Video codec proRes (Apple ProRes 422 HQ).
     *
     * @return VideoCodec
     */
    public static function proRes()
    {
        return new VideoCodec(VideoCodec::PRO_RES);
    }

    /**
     * Video codec h264.
     *
     * @param null $profile
     * @param null $level
     *
     * @return VideoCodec
     */
    public static function h264($profile = null, $level = null)
    {
        return (new VideoCodec(VideoCodec::H264))->profile($profile)->level($level);
    }

    /**
     * Video codec h265.
     *
     * @return VideoCodec
     */
    public static function h265()
    {
        return new VideoCodec(VideoCodec::H265);
    }

    /**
     * Video codec theora.
     *
     * @return VideoCodec
     */
    public static function theora()
    {
        return new VideoCodec(VideoCodec::THEORA);
    }
}
