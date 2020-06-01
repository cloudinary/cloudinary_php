<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Expression;

use Cloudinary\ClassUtils;
use UnexpectedValueException;

/**
 * Class BaseOperator
 *
 * @api
 */
abstract class BaseOperator extends BaseExpressionComponent
{
    /**
     * @var string $operator The operator.
     */
    protected $operator;

    // These static properties are defined in corresponding derived classes, otherwise they will be shared among
    // derived classes

    // protected static $operators;
    // protected static $friendlyRepresentations;

    /**
     * BaseOperator constructor.
     *
     * @param $operator
     */
    public function __construct($operator)
    {
        parent::__construct();

        $this->setOperator($operator);
    }

    /**
     * Gets supported operators.
     *
     * @return array
     */
    protected static function operators()
    {
        return self::getConstants(static::$operators);
    }

    /**
     * Gets friendly representations.
     *
     * @return array
     */
    protected static function friendlyRepresentations()
    {
        return static::$friendlyRepresentations;
    }

    /**
     * Sets the operator.
     *
     * @param string $operator The operator to set.
     */
    protected function setOperator($operator)
    {
        if (in_array($operator, self::operators(), false)) {
            $this->operator = $operator;

            return;
        }

        $friendlyRepresentations = static::friendlyRepresentations();

        if (isset($operator, $friendlyRepresentations)) {
            $this->operator = $friendlyRepresentations[$operator];

            return;
        }

        throw new UnexpectedValueException('Invalid ' . ClassUtils::getBaseName(static::class) . " $operator");
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->operator;
    }

    /**
     * Serializes to json.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return ['operator' => $this->operator];
    }
}
