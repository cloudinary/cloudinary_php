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

use Cloudinary\StringUtils;

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
class UVal extends Expression
{
    const STRING_MARKER             = '!';
    const STRING_ARR_DELIM          = ':';

    /**
     * The integer value expression component.
     *
     * @param int $value The int value.
     *
     * @return Expression
     */
    public static function int($value)
    {
        return self::uVal($value);
    }

    /**
     * The float value expression component.
     *
     * @param float $value The float value.
     *
     * @return self
     */
    public static function float($value)
    {
        return self::uVal($value);
    }

    /**
     * The numeric value expression component.
     *
     * @param mixed $value The numeric value. Can be used for aspect ratio, for example "16:9", etc.
     *
     * @return self
     */
    public static function numeric($value)
    {
        return self::uVal($value);
    }

    /**
     * The string value expression component.
     *
     * @param string $value The string value
     *
     * @return self
     */
    public static function string($value)
    {
        if (! StringUtils::startsWith($value, self::STRING_MARKER)) {
            $value = self::STRING_MARKER . $value;
        }

        if (! StringUtils::endsWith($value, self::STRING_MARKER)) {
            $value .= self::STRING_MARKER;
        }

        return self::uVal($value);
    }

    /**
     * The string array expression component.
     *
     * @param array $array The array of strings.
     *
     * @return self
     */
    public static function stringArray($array)
    {
        return self::string(implode(self::STRING_ARR_DELIM, $array));
    }

    /**
     * Named constructor.
     *
     * @param mixed $value The value.
     *
     * @return self
     */
    public static function uVal($value)
    {
        return new self($value);
    }
}
