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
 * href="https://cloudinary.com/documentation/user_defined_variables#supported_arithmetic_operators" target="_blank">
 * Supported arithmetic operators</a>
 *
 *
 * @api
 */
class ArithmeticOperator extends BaseOperator
{
    const ADD      = 'add';
    const SUBTRACT = 'sub';
    const MULTIPLY = 'mul';
    const DIVIDE   = 'div';
    const MODULO   = 'mod';
    const POWER    = 'pow';

    /**
     * @var array $operators The supported arithmetic operators.
     */
    protected static $operators;
    /**
     * @var array $friendlyRepresentations The user friendly representations of the arithmetic operators.
     */
    protected static $friendlyRepresentations = [
        '+' => self::ADD,
        '-' => self::SUBTRACT,
        '*' => self::MULTIPLY,
        '/' => self::DIVIDE,
        '%' => self::MODULO,
        '^' => self::POWER,
    ];

    /**
     * Add
     *
     * '+'
     *
     * @return ArithmeticOperator
     */
    public static function add()
    {
        return new self(self::ADD);
    }

    /**
     * Alias for ArithmeticOperator::add.
     *
     * +
     *
     * @return ArithmeticOperator
     *
     * @see ArithmeticOperator::add
     */
    public static function plus()
    {
        return self::add();
    }

    /**
     * Subtract
     *
     * -
     *
     * @return ArithmeticOperator
     */
    public static function subtract()
    {
        return new self(self::SUBTRACT);
    }

    /**
     * Alias for ArithmeticOperator::subtract.
     *
     * -
     *
     * @return ArithmeticOperator
     *
     * @see ArithmeticOperator::subtract
     */
    public static function minus()
    {
        return self::subtract();
    }

    /**
     * Multiply
     *
     * *
     *
     * @return ArithmeticOperator
     */
    public static function multiply()
    {
        return new static(static::MULTIPLY);
    }

    /**
     * Divide
     *
     * /
     *
     * @return ArithmeticOperator
     */
    public static function divide()
    {
        return new self(self::DIVIDE);
    }

    /**
     * Modulo (remainder)
     *
     * %
     *
     * @return ArithmeticOperator
     */
    public static function modulo()
    {
        return new self(self::MODULO);
    }

    /**
     * Power (to the power of)
     *
     * ^
     *
     * @return ArithmeticOperator
     */
    public static function power()
    {
        return new self(self::POWER);
    }
}
