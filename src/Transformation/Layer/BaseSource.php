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
use Cloudinary\Transformation\Qualifier\BaseQualifier;

/**
 * Class BaseLayer
 */
abstract class BaseSource extends BaseAction
{
    /**
     * @var CommonTransformation $transformation Transformation of the source.
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
        $this->qualifiers['source']->setStackPosition($position);

        return $this;
    }

    /**
     * Adds (chains) a transformation action.
     *
     * @param BaseAction|BaseQualifier|mixed $action The transformation action to add.
     *                                               If BaseQualifier is provided, it is wrapped with action.
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
     * @param FlagQualifier|string $flag The flag to add.
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

        return ArrayUtils::implodeUrl([$all, $this->getTransformation(), Flag::layerApply()]);
    }

    /**
     * Serializes to json.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'source'         => $this::getName(),
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
     * Gets the layer qualifier.
     *
     * @return BaseSourceQualifier
     *
     * @internal
     */
    abstract protected function getSourceQualifier();
}
