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

/**
 * Class BaseLayerContainer
 *
 * This is a base class for all layer containers (overlays/underlays).
 *
 * @internal
 */
abstract class BaseLayerContainer extends BaseAction
{
    /**
     * @var BaseLayer $layer The layer
     */
    protected $layer;

    /**
     * @var Position $position Layer position.
     */
    protected $position;

    /**
     * @var BlendMode $blendMode Layer blend mode.
     */
    protected $blendMode;

    /**
     * BaseLayerContainer constructor.
     *
     * @param BaseLayer|string $layer     The layer
     * @param Position         $position  Layer position.
     * @param string|BlendMode $blendMode Layer blend mode.
     */
    public function __construct($layer = null, $position = null, $blendMode = null)
    {
        parent::__construct();

        $this->layer($layer);
        $this->position($position);
        $this->blendMode($blendMode);
    }

    /**
     * Sets the layer.
     *
     * @param BaseLayer $layer The layer.
     *
     * @return static
     */
    abstract public function layer($layer);

    /**
     * Sets the layer position.
     *
     * @param Position $position The Position of the layer.
     *
     * @return static
     */
    abstract public function position($position = null);

    /**
     * Sets layer blend mode.
     *
     * @param BlendMode $blendMode The blend mode.
     *
     * @return static
     */
    public function blendMode($blendMode = null)
    {
        $this->blendMode = ClassUtils::verifyInstance($blendMode, EffectParam::class, BlendMode::class);

        return $this;
    }

    /**
     * Serializes to Cloudinary URL format
     *
     * @return string
     */
    public function __toString()
    {
        // We actually combine components...
        return ArrayUtils::implodeActionParams($this->layer, $this->position, $this->blendMode);
    }

    /**
     * Serializes to json.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'layer'      => $this->layer,
            'position'   => $this->position,
            'blend_mode' => $this->blendMode,
        ];
    }
}
