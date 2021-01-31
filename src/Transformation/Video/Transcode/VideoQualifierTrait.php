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

use Cloudinary\ClassUtils;

/**
 * Trait VideoQualifierTrait
 *
 * @api
 */
trait VideoQualifierTrait
{
    /**
     * Controls the video bitrate.
     *
     * Supported codecs: h264, h265 (MPEG-4); vp8, vp9 (WebM).
     *
     * @param int|string  $bitRate The number of bits used to represent the video data per second. By default the video
     *                             uses a variable bitrate (VBR), with this value indicating the maximum bitrate.
     *                             The value can be an integer e.g. 120000, or a string supporting "k" and "m"
     *                             (kilobits and megabits respectively) e.g. 250k or 2m.
     * @param null|string $type    The type of bitrate. If "constant" is specified, the video plays with a constant
     *                             bitrate (CBR). Use the constant defined in the BitRate class.
     *
     * @return BitRate
     *
     * @see \Cloudinary\Transformation\BitRate
     */
    public static function bitRate($bitRate, $type = null)
    {
        return new BitRate($bitRate, $type);
    }

    /**
     * Controls the range of acceptable FPS (Frames Per Second) to ensure that video (even when optimized) is
     * delivered with an expected FPS level (helps with sync to audio).
     *
     * @param int|null $min The minimum frame rate.
     * @param int|null $max The maximum frame rate.
     *
     * @return Fps
     */
    public static function fps($min = null, $max = null)
    {
        return new Fps($min, $max);
    }

    /**
     * Controls the range of acceptable FPS (Frames Per Second) to ensure that video (even when optimized) is
     * delivered with an expected FPS level (helps with sync to audio).
     *
     * @param int|null $min The minimum frame rate.
     * @param int|null $max The maximum frame rate.
     *
     * @return Fps
     */
    public static function fpsRange($min = null, $max = null)
    {
        return static::fps($min, $max);
    }

    /**
     * Sets the keyframe interval of the delivered video.
     *
     * @param int $interval The keyframe interval in seconds.
     *
     * @return KeyframeInterval
     */
    public static function keyframeInterval($interval)
    {
        return new KeyframeInterval($interval);
    }

    /**
     * Sets the streaming profile to apply to an HLS or MPEG-DASH adaptive bitrate streaming video.
     *
     * The value can be one of the pre-defined streaming profiles or a custom-defined one.
     * You can use the streaming profiles methods of StreamingProfilesTrait to get a list of the available streaming
     * profiles or to create new custom profiles.
     *
     * @param string $streamingProfile The streaming profile.
     *
     * @return StreamingProfile
     *
     * @see \Cloudinary\Api\Admin\StreamingProfilesTrait
     * @see https://cloudinary.com/documentation/video_manipulation_and_delivery#predefined_streaming_profiles
     */
    public static function streamingProfile($streamingProfile)
    {
        return ClassUtils::verifyInstance($streamingProfile, StreamingProfile::class);
    }

    /**
     * Sets the total number of frames to sample from the original video.
     *
     * Relevant when converting videos to animated GIF or WebP format. If not specified, the resulting GIF or WebP
     * samples the whole video (up to 400 frames, at up to 10 frames per second). By default the duration of the
     * animated image is the same as the duration of the video, no matter how many frames are sampled from the original
     * video (use the delay qualifier to adjust the amount of time between frames).
     *
     * @param int|string $value Integer - The total number of frames to sample from the original video. The frames are
     *                          spread out over the length of the video, e.g. 20 takes one frame every 5%.
     *                          String - The number of seconds between each frame to sample from the original video.
     *                          e.g. 2.3s takes one frame every 2.3 seconds.
     *
     * @return VideoSampling
     */
    public static function videoSampling($value)
    {
        return new VideoSampling($value);
    }
}
