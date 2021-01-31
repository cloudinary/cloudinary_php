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
 * Class ValueEffectQualifier
 */
class ValueEffectQualifier extends EffectQualifier
{
    /**
     * ValueEffectQualifier constructor.
     *
     * @param string $name   The name of the effect
     * @param mixed  $value  The value of the effect
     * @param array  $qualifiers Additional qualifiers.
     */
    public function __construct($name, $value = null, ...$qualifiers)
    {
        parent::__construct($name);

        $this->setEffectValue($value);
        $this->add(...$qualifiers);
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
        $this->value->setSimpleValue('value', $value);

        return $this;
    }
}
