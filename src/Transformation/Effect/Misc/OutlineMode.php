<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation;

/**
 * Class OutlineMode
 */
abstract class OutlineMode
{
    const INNER      = 'inner';
    const INNER_FILL = 'inner_fill';
    const OUTER      = 'outer';
    const FILL       = 'fill';

    /**
     * Outline mode inner.
     *
     * @return string
     */
    public static function inner()
    {
        return self::INNER;
    }

    /**
     * Outline mode inner fill.
     *
     * @return string
     */
    public static function innerFill()
    {
        return self::INNER_FILL;
    }

    /**
     * Outline mode outer.
     *
     * @return string
     */
    public static function outer()
    {
        return self::OUTER;
    }

    /**
     * Outline mode fill.
     *
     * @return string
     */
    public static function fill()
    {
        return self::FILL;
    }
}
