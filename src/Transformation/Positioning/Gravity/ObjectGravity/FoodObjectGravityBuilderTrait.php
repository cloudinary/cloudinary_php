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
 * Trait FoodObjectGravityBuilderTrait
 *
 * @api
 */
trait FoodObjectGravityBuilderTrait
{
    /**
     * Gravity food.
     *
     * @return ObjectGravity
     */
    public function food()
    {
        return $this->add(ObjectGravity::FOOD);
    }

    /**
     * Gravity banana.
     *
     * @return ObjectGravity
     */
    public function banana()
    {
        return $this->add(ObjectGravity::BANANA);
    }

    /**
     * Gravity apple.
     *
     * @return ObjectGravity
     */
    public function apple()
    {
        return $this->add(ObjectGravity::APPLE);
    }

    /**
     * Gravity sandwich.
     *
     * @return ObjectGravity
     */
    public function sandwich()
    {
        return $this->add(ObjectGravity::SANDWICH);
    }

    /**
     * Gravity orange.
     *
     * @return ObjectGravity
     */
    public function orange()
    {
        return $this->add(ObjectGravity::ORANGE);
    }

    /**
     * Gravity broccoli.
     *
     * @return ObjectGravity
     */
    public function broccoli()
    {
        return $this->add(ObjectGravity::BROCCOLI);
    }

    /**
     * Gravity carrot.
     *
     * @return ObjectGravity
     */
    public function carrot()
    {
        return $this->add(ObjectGravity::CARROT);
    }

    /**
     * Gravity hotdog.
     *
     * @return ObjectGravity
     */
    public function hotdog()
    {
        return $this->add(ObjectGravity::HOTDOG);
    }

    /**
     * Gravity pizza.
     *
     * @return ObjectGravity
     */
    public function pizza()
    {
        return $this->add(ObjectGravity::PIZZA);
    }

    /**
     * Gravity donut.
     *
     * @return ObjectGravity
     */
    public function donut()
    {
        return $this->add(ObjectGravity::DONUT);
    }

    /**
     * Gravity cake.
     *
     * @return ObjectGravity
     */
    public function cake()
    {
        return $this->add(ObjectGravity::CAKE);
    }
}
