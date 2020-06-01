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
     * @return ObjectGravity
     */
    public function electronic()
    {
        return $this->add(ObjectGravity::ELECTRONIC);
    }

    /**
     * Gravity tvMonitor.
     *
     * @return ObjectGravity
     */
    public function tvMonitor()
    {
        return $this->add(ObjectGravity::TV_MONITOR);
    }

    /**
     * Gravity laptop.
     *
     * @return ObjectGravity
     */
    public function laptop()
    {
        return $this->add(ObjectGravity::LAPTOP);
    }

    /**
     * Gravity mouse.
     *
     * @return ObjectGravity
     */
    public function mouse()
    {
        return $this->add(ObjectGravity::MOUSE);
    }

    /**
     * Gravity remote.
     *
     * @return ObjectGravity
     */
    public function remote()
    {
        return $this->add(ObjectGravity::REMOTE);
    }

    /**
     * Gravity keyboard.
     *
     * @return ObjectGravity
     */
    public function keyboard()
    {
        return $this->add(ObjectGravity::KEYBOARD);
    }

    /**
     * Gravity cellphone.
     *
     * @return ObjectGravity
     */
    public function cellphone()
    {
        return $this->add(ObjectGravity::CELLPHONE);
    }
}
