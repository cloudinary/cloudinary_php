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
 * Interface ObjectGravityInterface
 *
 * @package Cloudinary\Transformation
 */
interface ObjectGravityInterface extends
    AccessoryObjectGravityInterface,
    AnimalObjectGravityInterface,
    ApplianceObjectGravityInterface,
    ElectronicObjectGravityInterface,
    FoodObjectGravityInterface,
    FurnitureObjectGravityInterface,
    IndoorObjectGravityInterface,
    KitchenObjectGravityInterface,
    ObjectGravityPriorityInterface,
    OutdoorObjectGravityInterface,
    PersonObjectGravityInterface,
    VehicleObjectGravityInterface
{
}
