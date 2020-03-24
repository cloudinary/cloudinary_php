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
     * @return ObjectGravity
     */
    public function outdoor()
    {
        return $this->add(ObjectGravity::OUTDOOR);
    }

    /**
     * Gravity trafficLight.
     *
     * @return ObjectGravity
     */
    public function trafficLight()
    {
        return $this->add(ObjectGravity::TRAFFIC_LIGHT);
    }

    /**
     * Gravity stopSign.
     *
     * @return ObjectGravity
     */
    public function stopSign()
    {
        return $this->add(ObjectGravity::STOP_SIGN);
    }

    /**
     * Gravity parkingMeter.
     *
     * @return ObjectGravity
     */
    public function parkingMeter()
    {
        return $this->add(ObjectGravity::PARKING_METER);
    }

    /**
     * Gravity fireHydrant.
     *
     * @return ObjectGravity
     */
    public function fireHydrant()
    {
        return $this->add(ObjectGravity::FIRE_HYDRANT);
    }

    /**
     * Gravity bench.
     *
     * @return ObjectGravity
     */
    public function bench()
    {
        return $this->add(ObjectGravity::BENCH);
    }
}
