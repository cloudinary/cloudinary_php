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
     * @return ObjectGravity
     */
    public function kitchen()
    {
        return $this->add(ObjectGravity::KITCHEN);
    }

    /**
     * Gravity bottle.
     *
     * @return ObjectGravity
     */
    public function bottle()
    {
        return $this->add(ObjectGravity::BOTTLE);
    }

    /**
     * Gravity wineGlass.
     *
     * @return ObjectGravity
     */
    public function wineGlass()
    {
        return $this->add(ObjectGravity::WINE_GLASS);
    }

    /**
     * Gravity cup.
     *
     * @return ObjectGravity
     */
    public function cup()
    {
        return $this->add(ObjectGravity::CUP);
    }

    /**
     * Gravity fork.
     *
     * @return ObjectGravity
     */
    public function fork()
    {
        return $this->add(ObjectGravity::FORK);
    }

    /**
     * Gravity knife.
     *
     * @return ObjectGravity
     */
    public function knife()
    {
        return $this->add(ObjectGravity::KNIFE);
    }

    /**
     * Gravity spoon.
     *
     * @return ObjectGravity
     */
    public function spoon()
    {
        return $this->add(ObjectGravity::SPOON);
    }

    /**
     * Gravity bowl.
     *
     * @return ObjectGravity
     */
    public function bowl()
    {
        return $this->add(ObjectGravity::BOWL);
    }
}
