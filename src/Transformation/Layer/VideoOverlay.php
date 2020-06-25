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
use Cloudinary\Transformation\Parameter\VideoRange\VideoRange;

/**
 * Defines how the video layer is applied.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/video_manipulation_and_delivery#adding_video_overlays" target="_blank">
 * Video overlays</a>
 *
 * @api
 */
class VideoOverlay extends BaseLayerContainer
{
    /**
     * @var VideoRange $timelinePosition The timeline position of the overlay.
     */
    protected $timelinePosition;

    /**
     * BaseLayerContainer constructor.
     *
     * @param BaseLayer|string  $layer
     * @param BasePosition|null $position
     * @param VideoRange|null   $timelinePosition
     */
    public function __construct(
        $layer = null,
        $position = null,
        $timelinePosition = null
    ) {
        parent::__construct($layer, $position);

        $this->timelinePosition($timelinePosition);
    }

    /**
     * Sets the timeline position of the overlay.
     *
     * @param VideoRange|null $timelinePosition The timeline position of the overlay.
     *
     * @return BaseLayerContainer
     */
    public function timelinePosition(VideoRange $timelinePosition = null)
    {
        $this->timelinePosition = $timelinePosition;

        return $this;
    }

    /**
     * Indicates that the video should be concatenated on to the container video and not added as an overlay.
     *
     * @return $this
     */
    public function concatenate()
    {
        $this->layer->setFlag(LayerFlag::splice());

        return $this;
    }

    /**
     * Indicates that the video should be used as a cutter for the main video.
     *
     * @return $this
     */
    public function cutter()
    {
        $this->layer->setFlag(LayerFlag::cutter());

        return $this;
    }

    /**
     * Sets the layer.
     *
     * @param BaseLayer $layer The layer.
     *
     * @return static
     */
    public function layer($layer)
    {
        $this->layer = ClassUtils::verifyInstance($layer, BaseLayer::class, VideoLayer::class);

        return $this;
    }

    /**
     * Sets the layer position.
     *
     * @param Position $position The Position of the layer.
     *
     * @return static
     */
    public function position($position = null)
    {
        $this->position = ClassUtils::verifyInstance($position, BasePosition::class, AbsolutePosition::class);

        return $this;
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        return implode(',', array_filter([parent::__toString(), $this->timelinePosition]));
    }
}
