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
 * Trait ColorParamTrait
 *
 * @api
 */
trait ColorParamTrait
{
    /**
     * Sets the color.
     *
     * @param string $color The color.
     *
     * @return ColorParam
     */
    public static function color($color)
    {
        return new ColorParam($color);
    }
}
