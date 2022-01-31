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
 * Defines the supported operators for arithmetic expressions.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/user_defined_variables#arithmetic_expressions" target="_blank">
 * Arithmetic expressions</a>
 *
 *
 * @api
 */
class Expression extends BaseExpression
{
    use ArithmeticOperatorBuilderTrait;
    use RelationalOperatorBuilderTrait;
    use StringRelationalOperatorBuilderTrait;
    use LogicalOperatorBuilderTrait;

    /**
     * Creates an instance of Expression from a raw string.
     *
     * @param string $expression Arithmetic expression
     *
     * @return static
     */
    public static function expression($expression)
    {
        return new static($expression);
    }
}
