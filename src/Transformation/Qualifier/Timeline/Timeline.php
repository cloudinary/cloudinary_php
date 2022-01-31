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

use Cloudinary\ArrayUtils;
use Cloudinary\ClassUtils;
use Cloudinary\Transformation\Qualifier\BaseQualifier;

/**
 * Class Timeline
 */
class Timeline extends BaseQualifier
{
    /**
     * @var VideoOffset $offset Determines which part of a video to keep when it is trimmed.
     */
    protected $offset;

    /**
     * @var Duration $duration The length of the part of the video to keep.
     */
    protected $duration;

    /**
     * Region constructor.
     *
     * @param null $startOffset
     * @param null $endOffset
     * @param null $duration
     */
    public function __construct($startOffset = null, $endOffset = null, $duration = null)
    {
        parent::__construct();

        $this->offset((new VideoOffset())->startOffset($startOffset)->endOffset($endOffset));
        $this->duration($duration);
    }

    /**
     * Determines which part of a video to keep when it is trimmed.
     *
     * Each of the qualifiers can be specified either as a float representing the time in seconds or a string
     * representing the percentage of the video length (for example, "30%" or "30p").
     * For information and examples see 'Trimming videos' in the Video Transformations guide.
     *
     * @param mixed $startOffset The starting position of the part of the video to keep.
     * @param mixed $endOffset   The end position of the part of the video to keep.
     * @param mixed $duration    The length of the part of the video to keep.
     *
     * @return Timeline
     *
     * https://cloudinary.com/documentation/video_manipulation_and_delivery#trimming_videos
     */
    public static function position($startOffset = null, $endOffset = null, $duration = null)
    {
        return new self($startOffset, $endOffset, $duration);
    }

    /**
     * Sets the duration of the video to keep.
     *
     * @param mixed $duration The length of the part of the video to keep. This can be specified as a float
     *                        representing the time in seconds or a string representing the percentage of the
     *                        video length (for example, "30%" or "30p").
     *
     * @return $this
     */
    public function duration($duration)
    {
        $this->duration = ClassUtils::verifyInstance($duration, Duration::class);

        return $this;
    }

    /**
     * Sets the start and end points of the video to keep.
     *
     * @param VideoOffset|string $offset The start and end points of the video.
     *
     * @return $this
     */
    public function offset($offset)
    {
        $this->offset = ClassUtils::verifyInstance($offset, VideoOffset::class);

        return $this;
    }

    /**
     * Sets the starting position of the part of the video to keep when trimming videos.
     *
     * @param mixed $startOffset The starting position of the part of the video to keep. This can be specified as a
     *                           float representing the time in seconds or a string representing the percentage of the
     *                           video length (for example, "30%" or "30p").
     *
     * @return $this
     */
    public function startOffset($startOffset)
    {
        $this->offset->startOffset($startOffset);

        return $this;
    }

    /**
     * Sets the end position of the part of the video to keep when trimming videos.
     *
     * @param mixed $endOffset The end position of the part of the video to keep. This can be specified as a
     *                         float representing the time in seconds or a string representing the percentage of the
     *                         video length (for example, "30%" or "30p").
     *
     * @return $this
     */
    public function endOffset($endOffset)
    {
        $this->offset->endOffset($endOffset);

        return $this;
    }

    /**
     * Specify data which should be serialized to JSON
     */
    public function jsonSerialize()
    {
        $arr = [];

        if (! empty((string)$this->offset)) {
            $arr = array_merge($arr, $this->offset->jsonSerialize());
        }
        if (! empty((string)$this->duration)) {
            $arr = array_merge($arr, $this->duration->jsonSerialize());
        }

        return $arr;
    }

    /**
     * Collects and flattens action qualifiers.
     *
     * @return array A flat array of qualifiers
     *
     * @internal
     */
    public function getStringQualifiers()
    {
        $flatQualifiers = [];
        foreach (ArrayUtils::safeFilter([$this->offset, $this->duration]) as $qualifier) {
            $flatQualifiers = ArrayUtils::mergeNonEmpty($flatQualifiers, $qualifier->getStringQualifiers());
        }

        return $flatQualifiers;
    }

    /**
     * Serialize to Cloudinary URL format
     *
     * @return string
     */
    public function __toString()
    {
        return ArrayUtils::implodeActionQualifiers(...$this->getStringQualifiers());
    }
}
