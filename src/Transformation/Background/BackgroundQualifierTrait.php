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
 * Trait BackgroundQualifierTrait
 *
 * @api
 */
trait BackgroundQualifierTrait
{
    /**
     * Defines the background color to use.
     *
     * This applies to various scenarios including:
     *  * Defining the color to use instead of transparent background areas when converting to a format that does
     * not support transparency
     *  * Using the pad crop mode
     *  * Setting the color behind a text overlay
     *  * Setting the color behind subtitles
     *
     * @param array $value The background color.  Can be set as an RGB or RGBA hex triplet or quadruplet, a 3- or
     *                     4-digit RGB/RGBA hex, or a named color.
     *
     * @return Background
     */
    public static function background(...$value)
    {
        return new Background(...$value);
    }
}
