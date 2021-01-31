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
 * Trait OutdoorObjectGravityBuilderTrait
 *
 * @api
 */
trait OutdoorObjectGravityBuilderTrait
{
    /**
     * Gravity outdoor.
     *
     * @return string
     */
    public static function outdoor()
    {
        return ObjectGravity::OUTDOOR;
    }

    /**
     * Gravity trafficLight.
     *
     * @return string
     */
    public static function trafficLight()
    {
        return ObjectGravity::TRAFFIC_LIGHT;
    }

    /**
     * Gravity stopSign.
     *
     * @return string
     */
    public static function stopSign()
    {
        return ObjectGravity::STOP_SIGN;
    }

    /**
     * Gravity parkingMeter.
     *
     * @return string
     */
    public static function parkingMeter()
    {
        return ObjectGravity::PARKING_METER;
    }

    /**
     * Gravity fireHydrant.
     *
     * @return string
     */
    public static function fireHydrant()
    {
        return ObjectGravity::FIRE_HYDRANT;
    }

    /**
     * Gravity bench.
     *
     * @return string
     */
    public static function bench()
    {
        return ObjectGravity::BENCH;
    }
}
