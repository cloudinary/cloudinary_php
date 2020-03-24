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
use TypeError;

/**
 * Trait CompassGravityTrait
 */
trait CompassGravityTrait
{
    /**
     * Sets the compass direction.
     *
     * The compass direction represents a location in the image, for example, {@see Gravity::northEast} represents the
     * top right corner.
     *
     * @param $compassGravity
     *
     * @return $this
     */
    public function gravity($compassGravity)
    {
        if (! $compassGravity instanceof GravityParam) {
            $compassGravity = new CompassGravity($compassGravity);
        } elseif (! $compassGravity instanceof CompassGravity) {
            throw new TypeError(
                'CompassGravity expected, got: ' . ClassUtils::getClassName($compassGravity)
            );
        }

        $this->addParameter($compassGravity);

        return $this;
    }
}
