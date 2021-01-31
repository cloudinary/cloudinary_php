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

use Cloudinary\Transformation\Qualifier\BaseQualifier;

/**
 * Defines objects to use as the focal gravity of crops.
 *
 * **Learn more**: <a
 * href=https://cloudinary.com/documentation/cloudinary_object_aware_cropping_addon
 * target="_blank">Object-aware cropping</a>
 *
 * @api
 */
class ObjectGravity extends GravityQualifier implements ObjectGravityInterface
{
    use ObjectGravityTrait;

    /**
     * ObjectGravity constructor.
     *
     * @param string $objectName The name of the object.
     * @param mixed  $args       Optional fallback.
     */
    public function __construct($objectName = null, ...$args)
    {
        parent::__construct();

        $this->setQualifierValue($objectName);
        $this->add(...$args);
    }

    /**
     * Adds fallback gravity (usually AutoGravity).
     *
     * @param AutoGravity|ObjectGravity|string|mixed $fallbackGravity The fallback gravity.
     *
     * @return ObjectGravity
     */
    public function fallbackGravity($fallbackGravity)
    {
        if ($fallbackGravity instanceof BaseQualifier) {
            $fallbackGravity = $fallbackGravity->getValue();
        }

        return $this->add($fallbackGravity);
    }
}
