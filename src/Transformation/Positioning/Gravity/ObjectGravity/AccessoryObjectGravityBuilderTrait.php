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
 * Trait AccessoryObjectGravityBuilderTrait
 *
 * @api
 */
trait AccessoryObjectGravityBuilderTrait
{
    /**
     * Gravity accessory.
     *
     * @return string
     */
    public static function accessory()
    {
        return ObjectGravity::ACCESSORY;
    }

    /**
     * Gravity frisbee.
     *
     * @return string
     */
    public static function frisbee()
    {
        return ObjectGravity::FRISBEE;
    }

    /**
     * Gravity skis.
     *
     * @return string
     */
    public static function skis()
    {
        return ObjectGravity::SKIS;
    }

    /**
     * Gravity snowboard.
     *
     * @return string
     */
    public static function snowboard()
    {
        return ObjectGravity::SNOWBOARD;
    }

    /**
     * Gravity sportsBall.
     *
     * @return string
     */
    public static function sportsBall()
    {
        return ObjectGravity::SPORTS_BALL;
    }

    /**
     * Gravity kite.
     *
     * @return string
     */
    public static function kite()
    {
        return ObjectGravity::KITE;
    }

    /**
     * Gravity baseballBat.
     *
     * @return string
     */
    public static function baseballBat()
    {
        return ObjectGravity::BASEBALL_BAT;
    }

    /**
     * Gravity baseballGlove.
     *
     * @return string
     */
    public static function baseballGlove()
    {
        return ObjectGravity::BASEBALL_GLOVE;
    }

    /**
     * Gravity skateboard.
     *
     * @return string
     */
    public static function skateboard()
    {
        return ObjectGravity::SKATEBOARD;
    }

    /**
     * Gravity surfboard.
     *
     * @return string
     */
    public static function surfboard()
    {
        return ObjectGravity::SURFBOARD;
    }

    /**
     * Gravity tennisracket.
     *
     * @return string
     */
    public static function tennisracket()
    {
        return ObjectGravity::TENNIS_RACKET;
    }
}
