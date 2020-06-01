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
 * Trait ColorSpaceParamTrait
 *
 * @api
 */
trait ColorSpaceParamTrait
{
    /**
     * Controls the color space used for the delivered image.
     *
     * Use the constants defined in \Cloudinary\Transformation\ColorSpace for $colorSpace.
     *
     * @param string $colorSpace The color space.  Use the constants defined in the ColorSpace class.
     *
     * @return ColorSpace
     *
     * @see \Cloudinary\Transformation\ColorSpace
     *
     */
    public static function colorSpace($colorSpace)
    {
        return new ColorSpace($colorSpace);
    }
}
