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
 * Trait CropModeTrait
 *
 * @api
 */
trait ResizeQualifierTrait
{
    /**
     * Sets the crop mode.
     *
     * @param string $cropModeName The crop mode.  Use the constants defined in the CropMode class.
     *
     * @return CropMode
     *
     * @see \Cloudinary\Transformation\CropMode
     */
    public static function cropMode($cropModeName)
    {
        return new CropMode($cropModeName);
    }

    /**
     * Controls how much of the original image surrounding the face to keep when using either the 'crop' or 'thumb'
     * cropping modes with face detection.
     *
     * @param float $zoom The zoom factor. (Default: 1.0)
     *
     * @return Zoom
     */
    public static function zoom($zoom)
    {
        return new Zoom($zoom);
    }
}
