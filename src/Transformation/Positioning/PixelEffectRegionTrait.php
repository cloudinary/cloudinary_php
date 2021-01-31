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
 * Trait RegionTrait
 */
trait PixelEffectRegionTrait
{
    /**
     * Sets the faces region.
     *
     * @return Region
     */
    public static function faces()
    {
        return (new Region())->gravity(Gravity::faces());
    }

    /**
     * Sets the OCR region.
     *
     * @return Region
     */
    public static function ocr()
    {
        return (new Region())->gravity(Gravity::ocr());
    }

    /**
     * Sets the custom region.
     *
     * @param int|string $x
     * @param int|string $y
     * @param int|string $width
     * @param int|string $height
     *
     * @return Region
     */
    public static function custom($x = null, $y = null, $width = null, $height = null)
    {
        return new Region($x, $y, $width, $height);
    }
}
