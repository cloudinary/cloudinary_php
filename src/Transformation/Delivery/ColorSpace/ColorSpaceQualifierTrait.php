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
 * Trait ColorSpaceQualifierTrait
 *
 * @api
 */
trait ColorSpaceQualifierTrait
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

    /**
     * Specifies the ICC profile to use for the color space.
     *
     * The ICC file must be uploaded to your cloud as a raw, authenticated file.
     *
     * Alias for ColorSpace::icc
     *
     * @param string $publicId The public ID (including the file extension) of the ICC profile that defines the
     *                         color space.
     *
     * @return ColorSpace
     *
     * @see ColorSpace::icc
     */
    public static function colorSpaceFromIcc($publicId)
    {
        return ColorSpace::icc($publicId);
    }
}
