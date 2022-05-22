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

/**
 * Class BaseExpressionBuilder
 *
 * @api
 */
abstract class BaseExpression extends BaseExpressionComponent
{
    /**
     * @var ExpressionComponent $exprValue The expression value.
     */
    protected $exprValue;

    /**
     * ExpressionComponent constructor.
     *
     * @param $exprUserVariableName
     */
    public function __construct($exprUserVariableName)
    {
        parent::__construct();

        $this->exprValue = $exprUserVariableName;
    }

    /**
     * Internal expression builder.
     *
     * @param mixed $operator The operator to use for building the expression.
     *
     * @return ExpressionOperator
     */
    protected function buildExpression($operator)
    {
        return new ExpressionOperator(new ExpressionComponent($this->exprValue, $operator));
    }

    /**
     * Sets the right operand.
     *
     * @param $rightOperand
     *
     * @return Expression
     */
    protected function setRightOperand($rightOperand)
    {
        $this->exprValue->setRightOperand($rightOperand);

        return new Expression($this->exprValue);
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        return ExpressionUtils::normalize($this->exprValue);
    }


    /**
     * Serializes to JSON.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->exprValue->jsonSerialize();
    }
}
