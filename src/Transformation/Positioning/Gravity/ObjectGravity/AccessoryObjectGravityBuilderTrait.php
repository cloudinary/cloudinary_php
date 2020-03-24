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
     * @return ObjectGravity
     */
    public function accessory()
    {
        return $this->add(ObjectGravity::ACCESSORY);
    }

    /**
     * Gravity frisbee.
     *
     * @return ObjectGravity
     */
    public function frisbee()
    {
        return $this->add(ObjectGravity::FRISBEE);
    }

    /**
     * Gravity skis.
     *
     * @return ObjectGravity
     */
    public function skis()
    {
        return $this->add(ObjectGravity::SKIS);
    }

    /**
     * Gravity snowboard.
     *
     * @return ObjectGravity
     */
    public function snowboard()
    {
        return $this->add(ObjectGravity::SNOWBOARD);
    }

    /**
     * Gravity sportsBall.
     *
     * @return ObjectGravity
     */
    public function sportsBall()
    {
        return $this->add(ObjectGravity::SPORTS_BALL);
    }

    /**
     * Gravity kite.
     *
     * @return ObjectGravity
     */
    public function kite()
    {
        return $this->add(ObjectGravity::KITE);
    }

    /**
     * Gravity baseballBat.
     *
     * @return ObjectGravity
     */
    public function baseballBat()
    {
        return $this->add(ObjectGravity::BASEBALL_BAT);
    }

    /**
     * Gravity baseballGlove.
     *
     * @return ObjectGravity
     */
    public function baseballGlove()
    {
        return $this->add(ObjectGravity::BASEBALL_GLOVE);
    }

    /**
     * Gravity skateboard.
     *
     * @return ObjectGravity
     */
    public function skateboard()
    {
        return $this->add(ObjectGravity::SKATEBOARD);
    }

    /**
     * Gravity surfboard.
     *
     * @return ObjectGravity
     */
    public function surfboard()
    {
        return $this->add(ObjectGravity::SURFBOARD);
    }

    /**
     * Gravity tennisracket.
     *
     * @return ObjectGravity
     */
    public function tennisracket()
    {
        return $this->add(ObjectGravity::TENNIS_RACKET);
    }
}
