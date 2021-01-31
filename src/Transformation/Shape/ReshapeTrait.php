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

use Cloudinary\ClassUtils;

/**
 * Trait ReshapeTrait
 *
 * @api
 */
trait ReshapeTrait
{
    /**
     * Distorts the image to a new shape by adjusting its corners to achieve perception warping.
     *
     * Specify four PointValue objects, representing the new coordinates for each of the image's four corners,
     * in clockwise order from the top-left corner. For examples, see the Image Transformations guide.
     *
     * @param array $args The new coordinates of the image's corners.
     *
     * @see \Cloudinary\Transformation\Distort
     *
     * @return Distort
     *
     * @see https://cloudinary.com/documentation/image_transformations#image_shape_changes_and_distortion_effects
     */
    public static function distort(...$args)
    {
        return ClassUtils::verifyVarArgsInstance($args, Distort::class);
    }

    /**
     * Distorts the image to an arc shape.
     *
     * For examples, see the Image Transformations guide.
     *
     * @param float|string|mixed $degree The angle of distortion.  Positive values curve the image upwards (like a
     *                                   frown). Negative values curve the image downwards (like a smile).
     *
     *
     * @return EffectAction
     *
     * @see https://cloudinary.com/documentation/image_transformations#image_shape_changes_and_distortion_effects
     */
    public static function distortArc($degree)
    {
        if ($degree instanceof EffectAction) {
            return $degree;
        }

        return EffectAction::limited(ReshapeQualifier::DISTORT_ARC, EffectRange::ANGLE, $degree);
    }

    /**
     * Removes the edges of the image based on the color of the corner pixels.
     *
     * Specify a color other than the color of the corner pixels using the colorOverride() method of the
     * \Cloudinary\Transformation\TrimEffect class.
     *
     * @param int $tolerance The tolerance level for color similarity.  (Range: 0 to 100, Server default: 10)
     *
     * @return TrimEffect
     *
     * @see \Cloudinary\Transformation\TrimEffect
     *
     */
    public static function trim($tolerance = null)
    {
        return ClassUtils::forceInstance($tolerance, TrimEffect::class);
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

    /**
     * Trims pixels according to the transparency levels of a given overlay image.
     *
     * @param string|BaseSource         $source   The public ID of the image overlay.
     * @param Position|AbsolutePosition $position The position of the overlay with respect to the base image.
     *
     * @return CutByImage
     *
     * @see \Cloudinary\Transformation\CutByImage
     */
    public static function cutByImage($source, $position = null)
    {
        return new CutByImage($source, $position);
    }
}
