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
 * Trait ApplianceObjectGravityBuilderTrait
 *
 * @api
 */
trait ApplianceObjectGravityBuilderTrait
{
    /**
     * Gravity appliance.
     *
     * @return ObjectGravity
     */
    public function appliance()
    {
        return $this->add(ObjectGravity::APPLIANCE);
    }

    /**
     * Gravity microwave.
     *
     * @return ObjectGravity
     */
    public function microwave()
    {
        return $this->add(ObjectGravity::MICROWAVE);
    }

    /**
     * Gravity oven.
     *
     * @return ObjectGravity
     */
    public function oven()
    {
        return $this->add(ObjectGravity::OVEN);
    }

    /**
     * Gravity toaster.
     *
     * @return ObjectGravity
     */
    public function toaster()
    {
        return $this->add(ObjectGravity::TOASTER);
    }

    /**
     * Gravity sink.
     *
     * @return ObjectGravity
     */
    public function sink()
    {
        return $this->add(ObjectGravity::SINK);
    }

    /**
     * Gravity refrigerator.
     *
     * @return ObjectGravity
     */
    public function refrigerator()
    {
        return $this->add(ObjectGravity::REFRIGERATOR);
    }
}
