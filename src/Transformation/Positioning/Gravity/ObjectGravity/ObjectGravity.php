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
 * Class ObjectGravity
 *
 * @api
 */
class ObjectGravity extends GravityParam implements
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

    /**
     * ObjectGravity constructor.
     *
     * @param string $objectName The name of the object.
     * @param mixed $args Optional fallback.
     */
    public function __construct($objectName = null, ...$args)
    {
        parent::__construct();

        $this->setParamValue($objectName);
        $this->add(...$args);
    }
}
