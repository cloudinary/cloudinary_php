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
 * The relational operators for evaluating numeric expressions.
 *
 * @api
 */
class RelationalOperator extends BaseOperator
{
    const EQUAL                 = 'eq';
    const NOT_EQUAL             = 'ne';
    const LESS_THAN             = 'lt';
    const GREATER_THAN          = 'gt';
    const LESS_THAN_OR_EQUAL    = 'lte';
    const GREATER_THAN_OR_EQUAL = 'gte';

    /**
     * The supported relational operators.
     *
     * @var array $operators
     */
    protected static $operators;

    /**
     * The user friendly representations of the relational operators.
     *
     * @var array $friendlyRepresentations
     */
    protected static $friendlyRepresentations = [
        '='  => self::EQUAL,
        '!=' => self::NOT_EQUAL,
        '<'  => self::LESS_THAN,
        '>'  => self::GREATER_THAN,
        '<=' => self::LESS_THAN_OR_EQUAL,
        '>=' => self::GREATER_THAN_OR_EQUAL,
    ];

    /**
     * Equals.
     *
     * @return RelationalOperator
     */
    public static function equal()
    {
        return new static(self::EQUAL);
    }

    /**
     * Does not equal.
     *
     * @return RelationalOperator
     */
    public static function notEqual()
    {
        return new static(self::NOT_EQUAL);
    }

    /**
     * Less than.
     *
     * @return RelationalOperator
     */
    public static function lessThan()
    {
        return new static(static::LESS_THAN);
    }

    /**
     * Greater than.
     *
     * @return RelationalOperator
     */
    public static function greaterThan()
    {
        return new static(self::GREATER_THAN);
    }

    /**
     * Less than or equals.
     *
     * @return RelationalOperator
     */
    public static function lessThanOrEqual()
    {
        return new static(self::LESS_THAN_OR_EQUAL);
    }

    /**
     * Greater than or equals.
     *
     * @return RelationalOperator
     */
    public static function greaterThanOrEqual()
    {
        return new static(self::GREATER_THAN_OR_EQUAL);
    }
}
