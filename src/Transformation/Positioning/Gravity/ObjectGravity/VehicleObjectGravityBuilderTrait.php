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
     * @return string
     */
    public static function vehicle()
    {
        return ObjectGravity::VEHICLE;
    }

    /**
     * Gravity bicycle.
     *
     * @return string
     */
    public static function bicycle()
    {
        return ObjectGravity::BICYCLE;
    }

    /**
     * Gravity car.
     *
     * @return string
     */
    public static function car()
    {
        return ObjectGravity::CAR;
    }

    /**
     * Gravity motorbike.
     *
     * @return string
     */
    public static function motorbike()
    {
        return ObjectGravity::MOTORBIKE;
    }

    /**
     * Gravity aeroplane.
     *
     * @return string
     */
    public static function aeroplane()
    {
        return ObjectGravity::AEROPLANE;
    }

    /**
     * Gravity bus.
     *
     * @return string
     */
    public static function bus()
    {
        return ObjectGravity::BUS;
    }

    /**
     * Gravity train.
     *
     * @return string
     */
    public static function train()
    {
        return ObjectGravity::TRAIN;
    }

    /**
     * Gravity truck.
     *
     * @return string
     */
    public static function truck()
    {
        return ObjectGravity::TRUCK;
    }

    /**
     * Gravity boat.
     *
     * @return string
     */
    public static function boat()
    {
        return ObjectGravity::BOAT;
    }
}
