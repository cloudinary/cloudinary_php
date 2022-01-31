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
 * Trait CompassGravityTrait
 *
 * @api
 */
trait CompassGravityTrait
{
    /**
     * Sets the compass direction.
     *
     * The compass direction represents a location in the image, for example, Gravity::northEast() represents the
     * top right corner.
     *
     * @param $compassGravity
     *
     * @return static
     *
     * @see Gravity::northEast
     */
    public function gravity($compassGravity)
    {
        if (! $compassGravity instanceof GravityQualifier) {
            $compassGravity = new CompassGravity($compassGravity);
        } elseif (! $compassGravity instanceof CompassGravity) {
            throw new \UnexpectedValueException(
                'CompassGravity expected, got: ' . ClassUtils::getClassName($compassGravity)
            );
        }

        $this->addQualifier($compassGravity);

        return $this;
    }
}
