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
 * Class BaseLayer
 */
abstract class BaseLayer extends BaseAction
{
    use LayerFlagBuilderTrait;

    /**
     * @var CommonTransformation $transformation Transformation of the layer
     */
    protected $transformation;

    /**
     * Sets layer transformation.
     *
     * @param CommonTransformation $t The transformation to set.
     *
     * @return static
     */
    public function transformation(CommonTransformation $t)
    {
        $this->transformation = clone $t;

        return $this;
    }

    /**
     * Sets stack position of the layer.
     *
     * @param string $position The stack position.
     *
     * @return $this
     */
    public function setStackPosition($position)
    {
        $this->parameters['layer']->setStackPosition($position);

        return $this;
    }

    /**
     * Adds (chains) a transformation action.
     *
     * @param BaseAction|BaseParameter|mixed $action The transformation action to add.
     *                                               If BaseParameter is provided, it is wrapped with action.
     *
     * @return static
     */
    public function addAction($action)
    {
        $this->getTransformation()->addAction($action);

        return $this;
    }

    /**
     * Adds a flag as a separate action.
     *
     * @param FlagParameter|string $flag The flag to add.
     *
     * @return static
     */
    public function addFlag($flag)
    {
        $this->getTransformation()->addFlag($flag);

        return $this;
    }



    /**
     * Adds (appends) a transformation.
     *
     * Appended transformation is nested.
     *
     * @param CommonTransformation $transformation The transformation to add.
     *
     * @return static
     */
    public function addTransformation($transformation)
    {
        $this->getTransformation()->addTransformation($transformation);

        return $this;
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        $all = parent::__toString();

        $transformation = clone $this->getTransformation();

        $transformation->addAction(LayerFlag::layerApply());

        return ArrayUtils::implodeUrl([$all, (string)$transformation]);
    }

    /**
     * Serializes to json.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'layer'          => $this::getName(),
            'transformation' => $this->transformation,
        ];
    }

    /**
     * Gets the transformation.
     *
     * @return Transformation
     *
     * @internal
     */
    abstract public function getTransformation();

    /**
     * Gets the layer parameter.
     *
     * @return BaseLayerParam
     *
     * @internal
     */
    abstract protected function getLayerParam();
}
