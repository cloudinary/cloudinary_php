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
 * Trait FocalGravityTrait
 */
trait GravityTrait
{
    /**
     * Sets the gravity.
     *
     * @param mixed $gravity The gravity.
     *
     * @return $this
     */
    public function gravity($gravity)
    {
        return $this->addQualifier(ClassUtils::verifyInstance($gravity, GravityQualifier::class));
    }
}
