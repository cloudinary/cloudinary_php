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
 * Trait RelationalOperatorsTrait
 *
 * @api
 */
trait RelationalOperatorBuilderTrait
{
    /**
     * Equals.
     *
     * @return ExpressionOperator
     */
    public function equal()
    {
        return $this->buildExpression(RelationalOperator::equal());
    }

    /**
     * Does not equal.
     *
     * @return ExpressionOperator
     */
    public function notEqual()
    {
        return $this->buildExpression(RelationalOperator::notEqual());
    }

    /**
     * Less than.
     *
     * @return ExpressionOperator
     */
    public function lessThan()
    {
        return $this->buildExpression(RelationalOperator::lessThan());
    }

    /**
     * Greater than.
     *
     * @return ExpressionOperator
     */
    public function greaterThan()
    {
        return $this->buildExpression(RelationalOperator::greaterThan());
    }

    /**
     * Less than or equals.
     *
     * @return ExpressionOperator
     */
    public function lessThanOrEqual()
    {
        return $this->buildExpression(RelationalOperator::lessThanOrEqual());
    }

    /**
     * Greater than or equals.
     *
     * @return ExpressionOperator
     */
    public function greaterThanOrEqual()
    {
        return $this->buildExpression(RelationalOperator::greaterThanOrEqual());
    }
}
