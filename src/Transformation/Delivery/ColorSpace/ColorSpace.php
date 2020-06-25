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

use Cloudinary\Transformation\Parameter\BaseParameter;

/**
 * Controls the color space used for the delivered image.
 *
 * @api
 */
class ColorSpace extends BaseParameter
{
    /**
     * Render the image in the sRGB color space.
     */
    const SRGB = 'srgb';

    /**
     * Render the image using Facebook's truncated sRGB color space.
     */
    const TINY_SRGB = 'tinysrgb';

    /**
     * Render the image in the CMYK color space.
     */
    const CMYK = 'cmyk';

    /**
     * If the original image uses the CMYK color space, convert it to sRGB.
     */
    const NO_CMYK = 'no_cmyk';

    /**
     * Retain the CMYK color space when generating derived images.
     */
    const KEEP_CMYK = 'keep_cmyk';

    /**
     * Render the image using the specified color space (ICC) file.  The ICC file must be
     * uploaded to your account as a raw, authenticated file. Specify the ICC file using the icc method of the
     * this class.
     */
    const ICC = 'icc';

    /**
     * Specifies the ICC profile to use for the color space.
     *
     * The ICC file must be uploaded to your account as a raw, authenticated file.
     *
     * @param string $publicId The public ID (including the file extension) of the ICC profile that defines the
     *                         color space.
     *
     * @return ColorSpace
     *
     */
    public static function icc($publicId)
    {
        return new self(self::ICC, $publicId);
    }
}
