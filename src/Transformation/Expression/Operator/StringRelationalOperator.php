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
 * The relational operators for evaluating string expressions.
 *
 * @api
 */
class StringRelationalOperator extends BaseOperator
{
    const EQUAL     = 'eq';
    const NOT_EQUAL = 'ne';
    const IN        = 'in';
    const NOT_IN    = 'nin';

    /**
     * The supported string relational operators.
     *
     * @var array $operators
     */
    protected static $operators;

    /**
     * The user friendly representations of the string relational operators.
     *
     * @var array $friendlyRepresentations
     */
    protected static $friendlyRepresentations = [
        '='   => self::EQUAL,
        '!='  => self::NOT_EQUAL,
        'in'  => self::IN,
        'nin' => self::NOT_IN,
    ];

    /**
     * String Equals.
     *
     * @return StringRelationalOperator
     */
    public static function equal()
    {
        return new static(self::EQUAL);
    }

    /**
     * String does not equal.
     *
     * @return StringRelationalOperator
     */
    public static function notEqual()
    {
        return new static(self::NOT_EQUAL);
    }

    /**
     * Is in (a list of strings).
     *
     * @return StringRelationalOperator
     */
    public static function in()
    {
        return new static(self::IN);
    }

    /**
     * Is not in (a list of strings).
     *
     * @return StringRelationalOperator
     */
    public static function notIn()
    {
        return new static(self::NOT_IN);
    }
}
