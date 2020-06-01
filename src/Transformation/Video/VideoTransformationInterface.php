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

use Cloudinary\Transformation\Parameter\VideoRange\VideoRange;

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
     * @param VideoRange $range The part of the video to keep.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\Parameter\VideoRange\VideoRange
     */
    public function trim(VideoRange $range);

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
     * @param BaseLayer|string  $videoLayer       The overlay.
     * @param BasePosition|null $position         The position of the overlay.
     * @param VideoRange|null   $timelinePosition The timeline position of the overlay.
     *
     * @return static
     */
    public function overlay($videoLayer, $position = null, $timelinePosition = null);

    /**
     * Concatenates another video or image.
     *
     * @param VideoLayer|string $videoLayer       The layer to concatenate.
     * @param VideoRange        $timelinePosition The position of the concatenated video.
     *
     * @return static
     */
    public function concatenate($videoLayer, $timelinePosition = null);

    /**
     * Applies the video as a cutter for the main video.
     *
     * @param VideoLayer|string $videoLayer       The cutter video layer.
     * @param BasePosition|null $position         The position of the cutter.
     * @param VideoRange|null   $timelinePosition The timeline position of the cutter.
     *
     * @return static
     */
    public function cutter($videoLayer, $position = null, $timelinePosition = null);

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
     * @param AudioCodec|VideoCodec|AudioFrequency $transcode The new format.
     *
     * @return static
     */
    public function transcode($transcode);
}
