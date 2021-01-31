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
 * Trait ImageQualifierTrait
 *
 * @api
 */
trait ImageQualifierTrait
{
    /**
     * Default images can be used in the case that a requested image does not exist.
     *
     * @param string $defaultImage Default image public ID
     *
     * @return DefaultImage
     */
    public static function defaultImage($defaultImage)
    {
        return new DefaultImage($defaultImage);
    }

    /**
     * Controls the time delay between the frames of an animated image, in milliseconds.
     *
     * @param int $delay The delay in milliseconds
     *
     * @return Delay
     */
    public static function delay($delay)
    {
        return new Delay($delay);
    }

    /**
     * Controls the density to use when delivering an image or when converting a vector file such as a PDF or EPS
     * document to a web image delivery format.
     *
     * @param int|string $density The density in dpi.
     *
     * @return Density
     */
    public static function density($density)
    {
        return new Density($density);
    }

    /**
     * Prevents style class names collisions for sprite generation.
     *
     * @param string $prefix The style class name prefix.
     *
     * @return Prefix
     */
    public static function prefix($prefix)
    {
        return new Prefix($prefix);
    }
}
