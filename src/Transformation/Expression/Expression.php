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
 * Class Expression
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
    public static function raw($expression)
    {
        return new static($expression);
    }
}
