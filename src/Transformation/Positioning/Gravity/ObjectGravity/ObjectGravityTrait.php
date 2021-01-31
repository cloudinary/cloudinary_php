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
 * Trait ObjectGravityBuilderTrait
 *
 * @api
 */
trait ObjectGravityTrait
{
    use AccessoryObjectGravityBuilderTrait;
    use AnimalObjectGravityBuilderTrait;
    use ApplianceObjectGravityBuilderTrait;
    use ElectronicObjectGravityBuilderTrait;
    use FoodObjectGravityBuilderTrait;
    use FurnitureObjectGravityBuilderTrait;
    use IndoorObjectGravityBuilderTrait;
    use KitchenObjectGravityBuilderTrait;
    use ObjectGravityPriorityBuilderTrait;
    use OutdoorObjectGravityBuilderTrait;
    use PersonObjectGravityBuilderTrait;
    use VehicleObjectGravityBuilderTrait;
}
