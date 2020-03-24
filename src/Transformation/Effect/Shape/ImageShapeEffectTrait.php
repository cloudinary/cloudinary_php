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

use Cloudinary\Transformation\Argument\PointValue;

/**
 * Trait ImageShapeEffectTrait
 */
trait ImageShapeEffectTrait
{
    /**
     * Adds a shadow to the image.
     *
     * The shadow is offset by the x and y values specified in the $position parameter.
     *
     * @param int        $strength The strength of the shadow. (Range: 0 to 100, Server default: 40)
     * @param PointValue $position The position of the shadow. (Server default: bottom right)
     * @param string     $color    The color of the shadow (Server default: gray)
     *
     * @return Shadow
     * @see \Cloudinary\Transformation\Shadow
     *
     */
    public static function shadow($strength = null, $position = null, $color = null)
    {
        return new Shadow($strength, $position, $color);
    }

    /**
     * Distorts the image to a new shape by adjusting its corners to achieve perception warping.
     *
     * Specify four PointValue objects, representing the new coordinates for each of the image's four corners,
     * in clockwise order from the top-left corner. See
     * the {@see https://cloudinary.com/documentation/image_transformations#image_shape_changes_and_distortion_effects
     * image transformation guide} for examples.
     *
     * @param array $args The new coordinates of the image's corners.
     *
     * @see \Cloudinary\Transformation\Distort
     *
     * @return Distort
     */
    public static function distort(...$args)
    {
        return new Distort(...$args);
    }

    /**
     * Distorts the image to an arc shape.
     *
     * See
     * the {@see https://cloudinary.com/documentation/image_transformations#image_shape_changes_and_distortion_effects
     * image transformation guide} for examples.
     *
     * @param float $degree The angle of distortion.  Positive values curve the image upwards (like a frown).
     *                      Negative values curve the image downwards (like a smile).
     *
     *
     * @return EffectAction
     */
    public static function distortArc($degree)
    {
        return EffectAction::limited(ShapeEffect::DISTORT_ARC, EffectRange::ANGLE, $degree);
    }

    /**
     * Removes the edges of the image based on the color of the corner pixels.
     *
     * Specify a color other than the color of the corner pixels using
     * the {@see \Cloudinary\Transformation\TrimEffect::colorOverride() colorOverride} method of the
     * {@see \Cloudinary\Transformation\TrimEffect} class.
     *
     * @param int $tolerance The tolerance level for color similarity.  (Range: 0 to 100, Server default: 10)
     *
     * @return TrimEffect
     * @see \Cloudinary\Transformation\TrimEffect
     *
     */
    public static function trim($tolerance)
    {
        return new TrimEffect($tolerance);
    }

    /**
     * Skews the image according to the two specified values in degrees.
     *
     * @param float $skewX The angle of skew on the x-axis in degrees.
     * @param float $skewY The angle of skew on the y-axis in degrees.
     *
     * @return Shear
     * @see \Cloudinary\Transformation\Shear
     *
     */
    public static function shear($skewX = null, $skewY = null)
    {
        return new Shear($skewX, $skewY);
    }
}
