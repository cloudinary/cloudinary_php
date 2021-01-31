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
 * Trait ArithmeticExpressionOperatorsTrait
 *
 * @api
 */
trait ArithmeticOperatorBuilderTrait
{
    /**
     * Add.
     *
     * @return ExpressionOperator
     */
    public function add()
    {
        return $this->buildExpression(ArithmeticOperator::add());
    }

    /**
     * Subtract.
     *
     * @return ExpressionOperator
     */
    public function subtract()
    {
        return $this->buildExpression(ArithmeticOperator::subtract());
    }

    /**
     * Multiply.
     *
     * @return ExpressionOperator
     */
    public function multiply()
    {
        return $this->buildExpression(ArithmeticOperator::multiply());
    }

    /**
     * Divide.
     *
     * @return ExpressionOperator
     */
    public function divide()
    {
        return $this->buildExpression(ArithmeticOperator::divide());
    }

    /**
     * Modulo.
     *
     * @return ExpressionOperator
     */
    public function modulo()
    {
        return $this->buildExpression(ArithmeticOperator::modulo());
    }
}
