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
 * Interface VideoTransformationInterface
 *
 * @api
 */
interface VideoTransformationInterface extends CommonTransformationInterface
{
    /**
     * Shortens a video to the specified range.
     *
     * @param Timeline $range The part of the video to keep.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\Timeline
     */
    public function trim(Timeline $range);

    /**
     * Rounds the corners of a video.
     *
     * @param int|string $radius The radius of the corners in pixels.
     *
     * @return static
     */
    public function roundCorners($radius);

    /**
     * Adds another video, text or image as an overlay over the container video.
     *
     * @param BaseSource|string $videoLayer The overlay.
     * @param BasePosition|null $position   The position of the overlay.
     * @param Timeline|null     $timeline   The timeline position of the overlay.
     *
     * @return static
     */
    public function overlay($videoLayer, $position = null, $timeline = null);

    /**
     * Concatenates another video or image.
     *
     * @param Concatenate|VideoSource|string $videoLayer The layer to concatenate.
     *
     * @return static
     */
    public function concatenate($videoLayer);

    /**
     * Applies the video as a cutter for the main video.
     *
     * @param VideoSource|string $videoLayer The cutter video layer.
     * @param BasePosition|null  $position   The position of the cutter.
     * @param Timeline|null      $timeline   The timeline position of the cutter.
     *
     * @return static
     */
    public function cutter($videoLayer, $position = null, $timeline = null);

    /**
     * Adds subtitles to the video.
     *
     * @param string $subtitlesId The subtitles file public ID.
     *
     * @return mixed
     */
    public function addSubtitles($subtitlesId);

    /**
     * Transcodes the video (or audio) to another format.
     *
     * @param AudioCodec|VideoCodec|AudioFrequency|ToAnimatedAction $transcode The new format.
     *
     * @return static
     */
    public function transcode($transcode);
}
