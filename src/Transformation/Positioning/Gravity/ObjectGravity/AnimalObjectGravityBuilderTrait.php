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
 * Trait AnimalObjectGravityBuilderTrait
 *
 * @api
 */
trait AnimalObjectGravityBuilderTrait
{
    /**
     * Gravity animal.
     *
     * @return ObjectGravity
     */
    public function animal()
    {
        return $this->add(ObjectGravity::ANIMAL);
    }

    /**
     * Gravity bird.
     *
     * @return ObjectGravity
     */
    public function bird()
    {
        return $this->add(ObjectGravity::BIRD);
    }

    /**
     * Gravity cat.
     *
     * @return ObjectGravity
     */
    public function cat()
    {
        return $this->add(ObjectGravity::CAT);
    }

    /**
     * Gravity dog.
     *
     * @return ObjectGravity
     */
    public function dog()
    {
        return $this->add(ObjectGravity::DOG);
    }

    /**
     * Gravity horse.
     *
     * @return ObjectGravity
     */
    public function horse()
    {
        return $this->add(ObjectGravity::HORSE);
    }

    /**
     * Gravity sheep.
     *
     * @return ObjectGravity
     */
    public function sheep()
    {
        return $this->add(ObjectGravity::SHEEP);
    }

    /**
     * Gravity cow.
     *
     * @return ObjectGravity
     */
    public function cow()
    {
        return $this->add(ObjectGravity::COW);
    }

    /**
     * Gravity elephant.
     *
     * @return ObjectGravity
     */
    public function elephant()
    {
        return $this->add(ObjectGravity::ELEPHANT);
    }

    /**
     * Gravity bear.
     *
     * @return ObjectGravity
     */
    public function bear()
    {
        return $this->add(ObjectGravity::BEAR);
    }

    /**
     * Gravity zebra.
     *
     * @return ObjectGravity
     */
    public function zebra()
    {
        return $this->add(ObjectGravity::ZEBRA);
    }

    /**
     * Gravity giraffe.
     *
     * @return ObjectGravity
     */
    public function giraffe()
    {
        return $this->add(ObjectGravity::GIRAFFE);
    }
}
