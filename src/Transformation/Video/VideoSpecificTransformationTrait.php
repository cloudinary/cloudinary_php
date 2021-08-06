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
 * Trait VideoSpecificTransformationTrait
 *
 * @api
 */
trait VideoSpecificTransformationTrait
{
    use VideoTransformationFlagTrait;

    /**
     * Trims a video (and discards the rest).
     *
     * @param Timeline $range Specify the range of the video to leave.
     *
     * @return static
     *
     * @see https://cloudinary.com/documentation/video_manipulation_and_delivery#trimming_videos
     */
    public function trim(Timeline $range)
    {
        return $this->addAction($range);
    }

    /**
     * Rounds the corners of a video.
     *
     * @param int|string $radius The radius of the corners in pixels.
     *
     * @return static
     *
     * @see https://cloudinary.com/documentation/video_manipulation_and_delivery#rounding_corners_and_creating_circular_videos
     */
    public function roundCorners($radius)
    {
        return $this->addAction(ClassUtils::verifyInstance($radius, RoundCorners::class));
    }

    /**
     * Adds another video, text, image as an overlay over the container video.
     *
     * @param BaseSource|string $videoLayer The overlay.
     * @param BasePosition|null $position   The position of the overlay.
     * @param Timeline|null     $timeline   The timeline position of the overlay.
     *
     * @return static
     *
     * @see https://cloudinary.com/documentation/video_manipulation_and_delivery#adding_video_overlays
     */
    public function overlay($videoLayer, $position = null, $timeline = null)
    {
        return $this->addAction(
            ClassUtils::verifyInstance(
                $videoLayer,
                BaseSourceContainer::class,
                VideoOverlay::class,
                $position,
                $timeline
            )
        );
    }

    /**
     * Concatenates another video or image.
     *
     * @param VideoSource|string $videoSource The source of the video to concatenate.
     *
     * @return static
     *
     * @see https://cloudinary.com/documentation/video_manipulation_and_delivery#concatenating_videos
     */
    public function concatenate($videoSource)
    {
        return $this->addAction(ClassUtils::verifyInstance($videoSource, Concatenate::class));
    }

    /**
     * Applies the video as a cutter for the main video.
     *
     * @param VideoSource|string $videoLayer The cutter video layer.
     * @param BasePosition|null  $position   The position of the cutter.
     * @param Timeline|null      $timeline   The timeline position of the cutter.
     *
     * @return static
     */
    public function cutter($videoLayer, $position = null, $timeline = null)
    {
        return $this->addAction(
            (new VideoOverlay($videoLayer, $position, $timeline))->cutter()
        );
    }

    /**
     * Adds subtitles to the video.
     *
     * @param string $subtitlesId The subtitles file public ID.
     *
     * @return static
     */
    public function addSubtitles($subtitlesId)
    {
        return $this->overlay(VideoSource::subtitles($subtitlesId));
    }

    /**
     * Transcodes the video (or audio) to another format / adjusts encoding properties.
     *
     * @param AudioCodec|VideoCodec|AudioFrequency|mixed $transcode The new format or encoding property.
     *
     * @return static
     */
    public function transcode($transcode)
    {
        return $this->addAction($transcode);
    }

    /**
     * Controls the range of acceptable FPS (Frames Per Second) to ensure that video (even when optimized)
     * is delivered with an expected fps level (helps with sync to audio).
     *
     * @param float|int|string      $min The minimum frame rate.
     * @param float|int|string|null $max The maximum frame rate.
     *
     * @return static
     */
    public function fps($min, $max = null)
    {
        return $this->addAction(ClassUtils::verifyInstance($min, Fps::class, null, $max));
    }

    /**
     * Explicitly sets the keyframe interval of the delivered video.
     *
     * @param float $interval Positive float number in seconds.
     *
     * @return static
     */
    public function keyframeInterval($interval)
    {
        return $this->addAction(ClassUtils::verifyInstance($interval, KeyframeInterval::class));
    }

    /**
     * Controls the video bitrate.
     *
     * @param int|string $bitRate  The number of bits used to represent the video data per second. By default the video
     *                             uses a variable bitrate (VBR), with this value indicating the maximum bitrate.
     *                             Can be an integer e.g. 120000, or a string supporting "k" and "m"
     *                             (kilobits and megabits respectively) e.g. 250k or 2m.
     * @param string     $type     The type of bitrate. If "constant" is specified, the video plays with a constant
     *                             bitrate (CBR). Use the constant defined in the BitRate class.
     *
     * @return static
     */
    public function bitRate($bitRate, $type = null)
    {
        return $this->addAction(ClassUtils::verifyInstance($bitRate, BitRate::class, null, $type));
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
     * @return static
     *
     * @see https://cloudinary.com/documentation/video_manipulation_and_delivery#predefined_streaming_profiles
     *
     * @see \Cloudinary\Api\Admin\StreamingProfilesTrait
     */
    public function streamingProfile($streamingProfile)
    {
        return $this->addAction(ClassUtils::verifyInstance($streamingProfile, StreamingProfile::class));
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
     * @return static
     */
    public function videoSampling($value)
    {
        return $this->addAction(ClassUtils::verifyInstance($value, VideoSampling::class));
    }

    /**
     * Applies the specified video edit action.
     *
     * @param mixed $videoEdit The video edit action.
     *
     * @return static
     */
    public function videoEdit($videoEdit)
    {
        return $this->addAction($videoEdit);
    }
}
