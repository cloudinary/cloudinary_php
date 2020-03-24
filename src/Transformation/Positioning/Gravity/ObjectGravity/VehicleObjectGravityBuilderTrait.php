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
 * Trait VehicleObjectGravityBuilderTrait
 *
 * @api
 */
trait VehicleObjectGravityBuilderTrait
{
    /**
     * Gravity vehicle.
     *
     * @return ObjectGravity
     */
    public function vehicle()
    {
        return $this->add(ObjectGravity::VEHICLE);
    }

    /**
     * Gravity bicycle.
     *
     * @return ObjectGravity
     */
    public function bicycle()
    {
        return $this->add(ObjectGravity::BICYCLE);
    }

    /**
     * Gravity car.
     *
     * @return ObjectGravity
     */
    public function car()
    {
        return $this->add(ObjectGravity::CAR);
    }

    /**
     * Gravity motorbike.
     *
     * @return ObjectGravity
     */
    public function motorbike()
    {
        return $this->add(ObjectGravity::MOTORBIKE);
    }

    /**
     * Gravity aeroplane.
     *
     * @return ObjectGravity
     */
    public function aeroplane()
    {
        return $this->add(ObjectGravity::AEROPLANE);
    }

    /**
     * Gravity bus.
     *
     * @return ObjectGravity
     */
    public function bus()
    {
        return $this->add(ObjectGravity::BUS);
    }

    /**
     * Gravity train.
     *
     * @return ObjectGravity
     */
    public function train()
    {
        return $this->add(ObjectGravity::TRAIN);
    }

    /**
     * Gravity truck.
     *
     * @return ObjectGravity
     */
    public function truck()
    {
        return $this->add(ObjectGravity::TRUCK);
    }

    /**
     * Gravity boat.
     *
     * @return ObjectGravity
     */
    public function boat()
    {
        return $this->add(ObjectGravity::BOAT);
    }
}
