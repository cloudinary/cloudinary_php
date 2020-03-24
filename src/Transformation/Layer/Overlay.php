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
 * Class Overlay
 */
class Overlay extends BaseLayerContainer
{
    use ImageLayerTrait;

    /**
     * Sets the layer.
     *
     * @param BaseLayer|string $layer The layer.
     *
     * @return static
     */
    public function layer($layer)
    {
        $this->layer = ClassUtils::verifyInstance($layer, BaseLayer::class, ImageLayer::class);

        return $this;
    }

    /**
     * Sets the position of the layer.
     *
     * @param BasePosition $position The position.
     *
     * @return static
     */
    public function position($position = null)
    {
        $this->position = ClassUtils::verifyInstance($position, BasePosition::class, AbsolutePosition::class);

        return $this;
    }

    /**
     * Trim pixels according to the transparency levels of a given overlay image.
     *
     * @return $this
     */
    public function cutter()
    {
        $this->layer->setFlag(LayerFlag::cutter());

        return $this;
    }
}
