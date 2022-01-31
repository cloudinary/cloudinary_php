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
 * Trait FurnitureObjectGravityBuilderTrait
 *
 * @api
 */
trait FurnitureObjectGravityBuilderTrait
{
    /**
     * Gravity furniture.
     *
     * @return string
     */
    public static function furniture()
    {
        return ObjectGravity::FURNITURE;
    }

    /**
     * Gravity chair.
     *
     * @return string
     */
    public static function chair()
    {
        return ObjectGravity::CHAIR;
    }

    /**
     * Gravity sofa.
     *
     * @return string
     */
    public static function sofa()
    {
        return ObjectGravity::SOFA;
    }

    /**
     * Gravity pottedPlant.
     *
     * @return string
     */
    public static function pottedPlant()
    {
        return ObjectGravity::POTTED_PLANT;
    }

    /**
     * Gravity bed.
     *
     * @return string
     */
    public static function bed()
    {
        return ObjectGravity::BED;
    }

    /**
     * Gravity diningTable.
     *
     * @return string
     */
    public static function diningTable()
    {
        return ObjectGravity::DINING_TABLE;
    }

    /**
     * Gravity toilet.
     *
     * @return string
     */
    public static function toilet()
    {
        return ObjectGravity::TOILET;
    }
}
