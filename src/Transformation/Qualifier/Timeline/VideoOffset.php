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
use Cloudinary\Transformation\Argument\Range\Range;
use Cloudinary\Transformation\Qualifier\BaseQualifier;

/**
 * Class Offset
 */
class VideoOffset extends BaseQualifier
{
    /**
     * @var int|float|string $startOffset The starting position of the part of the video to keep.
     */
    protected $startOffset;

    /**
     * @var int|float|string $endOffset The end position of the part of the video to keep.
     */
    protected $endOffset;

    /**
     * Offset constructor.
     *
     * @param $rangeStr
     */
    public function __construct($rangeStr = null)
    {
        parent::__construct();

        if ($rangeStr === null) {
            return;
        }

        $range = new Range($rangeStr);

        $this->startOffset($range->startOffset);
        $this->endOffset($range->endOffset);
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
        if (! $startOffset instanceof StartOffset) {
            $startOffset = new StartOffset($startOffset);
        }

        $this->startOffset = $startOffset;

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
        if (! $endOffset instanceof EndOffset) {
            $endOffset = new EndOffset($endOffset);
        }

        $this->endOffset = $endOffset;

        return $this;
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
        return ArrayUtils::safeFilter([$this->startOffset, (string)$this->endOffset]);
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        return ArrayUtils::implodeActionQualifiers(...$this->getStringQualifiers());
    }

    /**
     * Serializes to json.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}
