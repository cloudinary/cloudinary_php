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
 * Trait AnimatedEditBuilderTrait
 *
 * @api
 */
trait AnimatedEditTrait
{
    /**
     * Controls the time delay between the frames of an animated image, in milliseconds.
     *
     * @param int $delay The delay in milliseconds
     *
     * @return AnimatedEdit
     */
    public function delay($delay)
    {
        return $this->addQualifier(ClassUtils::forceInstance($delay, Delay::class));
    }

    /**
     * Delivers an animated GIF that contains additional loops of the GIF.
     *
     * The total number of iterations is the number of additional loops plus one.
     *
     * You can also specify the loop effect without a numeric value to instruct it to loop the GIF infinitely.
     *
     * @param int $additionalIterations The additional number of times to play the animated GIF.
     *
     * @return AnimatedEdit
     */
    public function loop($additionalIterations = null)
    {
        return $this->addQualifier(ClassUtils::forceInstance($additionalIterations, Loop::class));
    }
}
