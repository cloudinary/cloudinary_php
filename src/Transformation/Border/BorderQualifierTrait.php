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
 * Trait BorderQualifierTrait
 *
 * @api
 */
trait BorderQualifierTrait
{
    /**
     * Adds a border around the image.
     *
     * @param array $value The color, width and style of the border. Currently, only
     *                     `solid` is supported for style. Colors can be set as an RGB or RGBA hex triplet or
     *                     quadruplet, a 3- or 4-digit RGB/RGBA hex, or a named color.
     *
     * @return BorderQualifier
     */
    public static function border(...$value)
    {
        return new BorderQualifier(...$value);
    }
}
