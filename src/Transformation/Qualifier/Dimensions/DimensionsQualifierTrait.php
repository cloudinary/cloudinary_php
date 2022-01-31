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

use Cloudinary\Transformation\Qualifier\Dimensions\Dpr;
use Cloudinary\Transformation\Qualifier\Dimensions\Height;
use Cloudinary\Transformation\Qualifier\Dimensions\Width;

/**
 * Trait DimensionsQualifierTrait
 *
 * @api
 */
trait DimensionsQualifierTrait
{
    /**
     * Sets the width of the asset.
     *
     * @param int|float $width The width in pixels (if an integer is specified) or as a percentage (if a float is
     *                         specified).
     *
     * @return Width
     *
     * @see \Cloudinary\Transformation\Qualifier\Dimensions\Width
     */
    public static function width($width)
    {
        return new Width($width);
    }

    /**
     * Sets the height of the asset.
     *
     * @param int|float $height The height in pixels (if an integer is specified) or as a percentage (if a float is
     *                         specified).
     *
     * @return Height
     *
     * @see \Cloudinary\Transformation\Qualifier\Dimensions\Height
     */
    public static function height($height)
    {
        return new Height($height);
    }

    /**
     * Sets the aspect ratio of the asset.
     *
     * @param float|array $aspectRatio The new aspect ratio, specified as a percentage or ratio.
     *
     * @return AspectRatio
     *
     * @see \Cloudinary\Transformation\Qualifier\Dimensions\AspectRatio
     */
    public static function aspectRatio(...$aspectRatio)
    {
        return new AspectRatio(...$aspectRatio);
    }

    /**
     * Sets the device pixel ratio.
     *
     * @param float $dpr The device pixel ratio.
     *
     * @return Dpr
     *
     * @see \Cloudinary\Transformation\Qualifier\Dimensions\Dpr
     */
    public static function dpr($dpr)
    {
        return new Dpr($dpr);
    }
}
