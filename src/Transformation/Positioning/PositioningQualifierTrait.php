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
 * Trait PositioningQualifierTrait
 *
 * @api
 */
trait PositioningQualifierTrait
{
    /**
     * Sets the x position in pixels.
     *
     * @param int $x The x position.
     *
     * @return X
     */
    public static function x($x)
    {
        return new X($x);
    }

    /**
     * Sets the y position in pixels.
     *
     * @param int $y The y position.
     *
     * @return Y
     */
    public static function y($y)
    {
        return new Y($y);
    }
}
