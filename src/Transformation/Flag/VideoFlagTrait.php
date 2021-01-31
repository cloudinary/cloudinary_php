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
 * Trait VideoFlagTrait
 *
 * @api
 */
trait VideoFlagTrait
{
    /**
     * If the requested video transformation has already been generated, this flag works identically to
     * Flag::attachment.
     *
     * However, if the video transformation is being requested for the first time, this flag causes the video download
     * to begin immediately, streaming it as a fragmented video file.
     *
     * In contrast, if the regular fl_attachment flag is used when a user requests a new video transformation,
     * the download will begin only after the complete transformed video has been generated.
     *
     * Most standard video players successfully play fragmented video files without issue.
     *
     * @param string $filename The attachment's filename
     *
     * @return FlagQualifier
     *
     * @see Flag::attachment
     */
    public static function streamingAttachment($filename = null)
    {
        return new FlagQualifier(self::STREAMING_ATTACHMENT, $filename);
    }

    /**
     * Deliver an HLS adaptive bitrate streaming file as HLS v3 instead of the default version (HLS v4).
     *
     * Delivering in this format requires a private CDN configuration.
     *
     * @return FlagQualifier
     *
     * @see https://cloudinary.com/documentation/video_manipulation_and_delivery#adaptive_bitrate_streaming_hls_and_mpeg_dash
     */
    public static function hlsv3()
    {
        return new FlagQualifier(self::HLSV3);
    }

    /**
     * Keep the Display Aspect Ratio metadata of the uploaded video (if it’s different from the current video
     * dimensions).
     *
     * @return FlagQualifier
     */
    public static function keepDar()
    {
        return new FlagQualifier(self::KEEP_DAR);
    }

    /**
     * Don't stream a video that is currently being generated on the fly. Wait until the video is fully generated.
     *
     * @return FlagQualifier
     */
    public static function noStream()
    {
        return new FlagQualifier(self::NO_STREAM);
    }

    /**
     * Convert the audio channel to mono
     *
     * @return FlagQualifier
     */
    public static function mono()
    {
        return new FlagQualifier(self::MONO);
    }

    /**
     * Truncate (trim) a video file based on the start time defined in the metadata (relevant only where the metadata
     * includes a directive to play only a section of the video).
     *
     * @return FlagQualifier
     */
    public static function truncateTS()
    {
        return new FlagQualifier(self::TRUNCATE_TS);
    }

    /**
     * Create a waveform image (in the format specified by the file extension) from the audio or video file.
     *
     * @return FlagQualifier
     */
    public static function waveform()
    {
        return new FlagQualifier(self::WAVEFORM);
    }
}
