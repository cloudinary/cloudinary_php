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
 * Trait AnimatedImageTransformationTrait
 *
 * @api
 */
trait AnimatedImageTransformationTrait
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
}
