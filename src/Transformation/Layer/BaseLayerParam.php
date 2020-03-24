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
use Cloudinary\Transformation\Parameter\BaseParameter;

/**
 * Class BaseLayerParam
 */
class BaseLayerParam extends BaseParameter
{
    /**
     * @var string $name The name of the layer parameter.
     */
    protected static $name = 'layer';

    /**
     * @var string $layerType The type of the layer.
     */
    protected $layerType;

    /**
     * @var string The stack position of the layer.
     */
    protected $stackPosition = LayerStackPosition::OVERLAY;

    /**
     * Gets the layer key.
     *
     * Key depends on the stack position.
     *
     * @return string
     *
     * @internal
     */
    public function getLayerKey()
    {
        if ($this->stackPosition === LayerStackPosition::UNDERLAY) {
            return 'u';
        }

        return 'l';
    }

    /**
     * Gets component full name.
     *
     * @return string Component name.
     */
    public function getFullName()
    {
        return ArrayUtils::implodeFiltered('_', [parent::getFullName(), $this->stackPosition]);
    }

    /**
     * Sets stack position of the layer.
     *
     * @param string $stackPosition The stack position.
     *
     * @return $this
     */
    public function setStackPosition($stackPosition)
    {
        $this->stackPosition = $stackPosition;

        return $this;
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        $layerTypeStr = $this->layerType ? "$this->layerType:" : '';

        return empty((string)$this->value) ? '' : "{$this->getLayerKey()}_{$layerTypeStr}{$this->value}";
    }
}
