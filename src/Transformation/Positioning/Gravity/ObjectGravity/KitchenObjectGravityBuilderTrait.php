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
 * Trait KitchenObjectGravityBuilderTrait
 *
 * @api
 */
trait KitchenObjectGravityBuilderTrait
{
    /**
     * Gravity kitchen.
     *
     * @return string
     */
    public static function kitchen()
    {
        return ObjectGravity::KITCHEN;
    }

    /**
     * Gravity bottle.
     *
     * @return string
     */
    public static function bottle()
    {
        return ObjectGravity::BOTTLE;
    }

    /**
     * Gravity wineGlass.
     *
     * @return string
     */
    public static function wineGlass()
    {
        return ObjectGravity::WINE_GLASS;
    }

    /**
     * Gravity cup.
     *
     * @return string
     */
    public static function cup()
    {
        return ObjectGravity::CUP;
    }

    /**
     * Gravity fork.
     *
     * @return string
     */
    public static function fork()
    {
        return ObjectGravity::FORK;
    }

    /**
     * Gravity knife.
     *
     * @return string
     */
    public static function knife()
    {
        return ObjectGravity::KNIFE;
    }

    /**
     * Gravity spoon.
     *
     * @return string
     */
    public static function spoon()
    {
        return ObjectGravity::SPOON;
    }

    /**
     * Gravity bowl.
     *
     * @return string
     */
    public static function bowl()
    {
        return ObjectGravity::BOWL;
    }
}
