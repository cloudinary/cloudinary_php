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
 * Trait ElectronicObjectGravityBuilderTrait
 *
 * @api
 */
trait ElectronicObjectGravityBuilderTrait
{
    /**
     * Gravity electronic.
     *
     * @return string
     */
    public static function electronic()
    {
        return ObjectGravity::ELECTRONIC;
    }

    /**
     * Gravity tvMonitor.
     *
     * @return string
     */
    public static function tvMonitor()
    {
        return ObjectGravity::TV_MONITOR;
    }

    /**
     * Gravity laptop.
     *
     * @return string
     */
    public static function laptop()
    {
        return ObjectGravity::LAPTOP;
    }

    /**
     * Gravity mouse.
     *
     * @return string
     */
    public static function mouse()
    {
        return ObjectGravity::MOUSE;
    }

    /**
     * Gravity remote.
     *
     * @return string
     */
    public static function remote()
    {
        return ObjectGravity::REMOTE;
    }

    /**
     * Gravity keyboard.
     *
     * @return string
     */
    public static function keyboard()
    {
        return ObjectGravity::KEYBOARD;
    }

    /**
     * Gravity cellphone.
     *
     * @return string
     */
    public static function cellphone()
    {
        return ObjectGravity::CELLPHONE;
    }
}
