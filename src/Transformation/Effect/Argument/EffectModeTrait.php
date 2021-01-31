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
 * Trait EffectModeTrait
 *
 * @property QualifierMultiValue $value
 */
trait EffectModeTrait
{
    /**
     * Sets the effect mode.
     *
     * @param string|mixed $mode The mode to set.
     *
     * @return $this
     */
    public function mode($mode)
    {
        $this->value->setValue(ClassUtils::verifyInstance($mode, EffectMode::class));

        return $this;
    }
}
