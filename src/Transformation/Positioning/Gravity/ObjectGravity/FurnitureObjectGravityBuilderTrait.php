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
     * @return ObjectGravity
     */
    public function furniture()
    {
        return $this->add(ObjectGravity::FURNITURE);
    }

    /**
     * Gravity chair.
     *
     * @return ObjectGravity
     */
    public function chair()
    {
        return $this->add(ObjectGravity::CHAIR);
    }

    /**
     * Gravity sofa.
     *
     * @return ObjectGravity
     */
    public function sofa()
    {
        return $this->add(ObjectGravity::SOFA);
    }

    /**
     * Gravity pottedPlant.
     *
     * @return ObjectGravity
     */
    public function pottedPlant()
    {
        return $this->add(ObjectGravity::POTTED_PLANT);
    }

    /**
     * Gravity bed.
     *
     * @return ObjectGravity
     */
    public function bed()
    {
        return $this->add(ObjectGravity::BED);
    }

    /**
     * Gravity diningTable.
     *
     * @return ObjectGravity
     */
    public function diningTable()
    {
        return $this->add(ObjectGravity::DINING_TABLE);
    }

    /**
     * Gravity toilet.
     *
     * @return ObjectGravity
     */
    public function toilet()
    {
        return $this->add(ObjectGravity::TOILET);
    }
}
