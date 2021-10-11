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
 * Trait ThemeEffectTrait
 *
 * @api
 */
trait ThemeEffectTrait
{
    /**
     * Changes the main background color to the one specified, as if a 'theme change' was applied
     * (e.g. dark mode vs light mode).
     *
     * @param string  $color            The target background color. Specify either the name of a color (e.g. black,
     *                                  lightgray), or an RGB hex value (e.g. f0ebe6).
     * @param integer $photoSensitivity The sensitivity to photographic elements of an image. A value of 0 treats the
     *                                  whole image as non-photographic. A value of 200 treats the whole image as
     *                                  photographic, so no theme change is applied. Range: 0 to 200. Default: 100.
     *
     * @return ThemeEffect
     *
     * @see \Cloudinary\Transformation\ThemeEffect
     * @see \Cloudinary\Transformation\ThemeQualifier
     */
    public static function theme($color, $photoSensitivity = null)
    {
        return new ThemeEffect($color, $photoSensitivity);
    }
}
