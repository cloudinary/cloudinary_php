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
 * Trait PageNumberTrait
 *
 * @api
 */
trait PageNumberTrait
{
    /**
     * Creates a new instance using the specified number.
     *
     * @param int $value The number.
     *
     * @return static
     */
    public static function number($value)
    {
        return static::createWithPageParam($value);
    }
}
