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
 * Trait IndoorObjectGravityBuilderTrait
 *
 * @api
 */
trait IndoorObjectGravityBuilderTrait
{
    /**
     * Gravity indoor.
     *
     * @return ObjectGravity
     */
    public function indoor()
    {
        return $this->add(ObjectGravity::INDOOR);
    }

    /**
     * Gravity book.
     *
     * @return ObjectGravity
     */
    public function book()
    {
        return $this->add(ObjectGravity::BOOK);
    }

    /**
     * Gravity clock.
     *
     * @return ObjectGravity
     */
    public function clock()
    {
        return $this->add(ObjectGravity::CLOCK);
    }

    /**
     * Gravity vase.
     *
     * @return ObjectGravity
     */
    public function vase()
    {
        return $this->add(ObjectGravity::VASE);
    }

    /**
     * Gravity scissors.
     *
     * @return ObjectGravity
     */
    public function scissors()
    {
        return $this->add(ObjectGravity::SCISSORS);
    }

    /**
     * Gravity teddyBear.
     *
     * @return ObjectGravity
     */
    public function teddyBear()
    {
        return $this->add(ObjectGravity::TEDDY_BEAR);
    }

    /**
     * Gravity hairDrier.
     *
     * @return ObjectGravity
     */
    public function hairDrier()
    {
        return $this->add(ObjectGravity::HAIR_DRIER);
    }

    /**
     * Gravity toothbrush.
     *
     * @return ObjectGravity
     */
    public function toothbrush()
    {
        return $this->add(ObjectGravity::TOOTHBRUSH);
    }
}
