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
 * Defines the logical operators for chaining conditional transformations.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/conditional_transformations#multiple_conditions" target="_blank">
 * Multiple transformation conditions</a>
 *
 *
 * @api
 */
class LogicalOperator extends BaseOperator
{
    const AND_OPERATOR = 'and';
    const OR_OPERATOR  = 'or';

    /**
     * @var array $operators The supported logical operators.
     */
    protected static $operators;
    /**
     * @var array $friendlyRepresentations The user friendly representations of the logical operators.
     */
    protected static $friendlyRepresentations = [
        '&&' => self::AND_OPERATOR,
        '||' => self::OR_OPERATOR,
    ];

    /**
     * And.
     *
     * @return LogicalOperator
     */
    public static function andOperator()
    {
        return new static(self::AND_OPERATOR);
    }

    /**
     * Or.
     *
     * @return LogicalOperator
     */
    public static function orOperator()
    {
        return new static(self::OR_OPERATOR);
    }
}
