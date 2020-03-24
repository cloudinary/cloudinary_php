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
 * Class BaseValueEffect
 */
class ValueEffectParam extends EffectParam
{
    /**
     * BaseValueEffect constructor.
     *
     * @param string $name   The name of the effect
     * @param mixed  $value  The value of the effect
     * @param array  $params Additional parameters.
     */
    public function __construct($name, $value = null, ...$params)
    {
        parent::__construct($name);

        $this->setEffectValue($value);
        $this->add(...$params);
    }

    /**
     * Internal setter of the effect value.
     *
     * @param mixed $value The value to set.
     *
     * @return $this
     *
     * @internal
     */
    public function setEffectValue($value)
    {
        if (empty($value)) {
            return $this;
        }

        $this->value->setSimpleValue('value', $value);

        return $this;
    }
}
