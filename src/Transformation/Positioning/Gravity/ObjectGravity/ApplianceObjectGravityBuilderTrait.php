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
     * @return string
     */
    public static function appliance()
    {
        return ObjectGravity::APPLIANCE;
    }

    /**
     * Gravity microwave.
     *
     * @return string
     */
    public static function microwave()
    {
        return ObjectGravity::MICROWAVE;
    }

    /**
     * Gravity oven.
     *
     * @return string
     */
    public static function oven()
    {
        return ObjectGravity::OVEN;
    }

    /**
     * Gravity toaster.
     *
     * @return string
     */
    public static function toaster()
    {
        return ObjectGravity::TOASTER;
    }

    /**
     * Gravity sink.
     *
     * @return string
     */
    public static function sink()
    {
        return ObjectGravity::SINK;
    }

    /**
     * Gravity refrigerator.
     *
     * @return string
     */
    public static function refrigerator()
    {
        return ObjectGravity::REFRIGERATOR;
    }
}
