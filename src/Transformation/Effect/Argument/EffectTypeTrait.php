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

use Cloudinary\ClassUtils;

/**
 * Trait EffectTypeTrait
 *
 * @property QualifierMultiValue $value
 */
trait EffectTypeTrait
{
    /**
     * Sets the effect type.
     *
     * @param string|mixed $type The type to set.
     *
     * @return $this
     */
    public function type($type)
    {
        $this->value->setValue(ClassUtils::verifyInstance($type, EffectType::class));

        return $this;
    }
}
