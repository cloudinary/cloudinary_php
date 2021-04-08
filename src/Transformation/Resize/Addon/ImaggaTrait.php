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
 * Trait ImaggaTrait
 *
 * @api
 */
trait ImaggaTrait
{
    /**
     * Generates the best cropped version of the original image using the Imagga add-on.
     *
     * If no dimensions specified, Imagga automatically defines the best crop resolution.
     *
     * @see https://cloudinary.com/documentation/imagga_crop_and_scale_addon
     *
     * @param int|float|string|null $width       The required width of a transformed asset.
     * @param int|float|null        $height      The required height of a transformed asset.
     *
     * @return Imagga
     */
    public static function imaggaCrop($width = null, $height = null)
    {
        return static::createImagga(CropMode::IMAGGA_CROP, $width, $height);
    }

    /**
     * Generates a smartly scaled image that perfectly fits the requested dimensions.
     *
     * @see https://cloudinary.com/documentation/imagga_crop_and_scale_addon
     *
     * @param int|float|string|null $width       The required width of a transformed asset.
     * @param int|float|null        $height      The required height of a transformed asset.
     *
     * @return Imagga
     */
    public static function imaggaScale($width = null, $height = null)
    {
        return static::createImagga(CropMode::IMAGGA_SCALE, $width, $height);
    }

    /**
     * @param mixed ...$args
     *
     * @return Imagga
     *
     * @internal
     */
    protected static function createImagga(...$args)
    {
        return new Imagga(...$args);
    }
}
