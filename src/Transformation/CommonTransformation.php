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
use Cloudinary\Transformation\Parameter\BaseParameter;

/**
 * Class CommonTransformation
 */
class CommonTransformation extends BaseComponent implements CommonTransformationInterface
{
    use CommonTransformationTrait;

    /**
     * @var array $actions The components (actions) of the transformation.
     */
    protected $actions = [];

    /**
     * CommonTransformation constructor.
     *
     * @param null $transformation
     */
    public function __construct($transformation = null)
    {
        parent::__construct();

        if ($transformation === null) {
            return;
        }

        if (is_string($transformation)) {
            $this->actions [] = $transformation; // TODO: wrap with some GenericTransformation class
        } elseif (is_array($transformation)) {
            $this->addActionFromParams($transformation);
        } elseif ($transformation instanceof self) {
            $this->addTransformation($transformation);
        } else {
            $this->addAction($transformation);
        }
    }

    /**
     * Transformation named constructor.
     *
     * @param $transformation
     *
     * @return static
     */
    public static function generic($transformation)
    {
        return new static($transformation);
    }

    /**
     * Creates a new Transformation instance from an array of transformation parameters.
     *
     * @param array $params An array of transformation parameters.
     *
     * @return static
     */
    public static function fromParams($params)
    {
        return (new static())->addActionFromParams($params);
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
        if ($action instanceof BaseParameter) {
            $action = new Action($action);
        }

        $this->actions[] = $action;

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
        if (! $transformation instanceof self) {
            $transformation = new CommonTransformation($transformation);
        }

        $this->actions[] = $transformation;

        return $this;
    }

    /**
     * Deep copy of the transformation.
     */
    public function __clone()
    {
        $this->actions = ArrayUtils::deepCopy($this->actions);
    }

    /**
     * Serializes transformation to URL.
     *
     * @param ImageTransformation|string|null $withTransformation Optional transformation to append.
     *
     * @return string
     */
    public function toUrl($withTransformation = null)
    {
        if ($withTransformation === null) {
            return ArrayUtils::implodeUrl($this->actions);
        }

        if (empty($this->actions)) {
            return (string)$withTransformation;
        }

        $resultingComponents   = ArrayUtils::deepCopy($this->actions);
        $resultingComponents[] = $withTransformation;

        return ArrayUtils::implodeUrl($resultingComponents);
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toUrl();
    }

    /**
     * Serializes to json.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return ['name' => $this->getFullName(), 'actions' => $this->actions];
    }
}
