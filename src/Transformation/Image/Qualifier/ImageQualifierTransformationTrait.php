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
 * Trait ImageQualifierTransformationTrait
 *
 * @api
 */
trait ImageQualifierTransformationTrait
{
    /**
     * Sets the delay between frames of an animated image in milliseconds.
     *
     * @param Delay|int $delay
     *
     * @return static
     */
    public function delay($delay)
    {
        return $this->addAction(ClassUtils::verifyInstance($delay, Delay::class));
    }

    /**
     * Default images can be used in the case that a requested image does not exist.
     *
     * @param string $defaultImage Default image public ID
     *
     * @return $this
     */
    public function defaultImage($defaultImage)
    {
        return $this->addAction(ClassUtils::verifyInstance($defaultImage, DefaultImage::class));
    }

    /**
     * Controls the density to use when delivering an image or when converting a vector file such as a PDF or EPS
     * document to a web image delivery format.
     *
     * @param int|string $density The density in dpi.
     *
     * @return Density
     */
    public function density($density)
    {
        return $this->addAction(ClassUtils::verifyInstance($density, Density::class));
    }

    /**
     * Prevents style class names collisions for sprite generation.
     *
     * @param string $prefix The style class name prefix.
     *
     * @return Prefix
     */
    public function prefix($prefix)
    {
        return $this->addAction(ClassUtils::verifyInstance($prefix, Prefix::class));
    }
}
