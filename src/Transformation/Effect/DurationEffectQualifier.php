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
 * Class DurationEffectQualifier
 *
 * This class is used for all effects that have a duration value.
 *
 * @internal
 */
class DurationEffectQualifier extends ValueEffectQualifier
{
    /**
     * @var bool Indicates whether to inverse the provided value.
     */
    protected $inverseValue = false;

    /**
     * DurationEffectQualifier constructor.
     *
     * @param string $name         The name of the effect
     * @param mixed  $value        The value of the effect
     * @param bool   $inverseValue Indicates whether to inverse the provided value.
     * @param array  $qualifiers       Additional qualifiers.
     */
    public function __construct($name, $value = null, $inverseValue = false, ...$qualifiers)
    {
        parent::__construct($name);

        $this->inverseValue = $inverseValue;

        $this->duration($value);
        $this->add(...$qualifiers);
    }



    /**
     * Setter of the effect duration.
     *
     * @param int $duration The duration to set.
     *
     * @return DurationEffectQualifier
     *
     * @internal
     */
    public function duration($duration)
    {
        if ($this->inverseValue && is_int($duration) && $duration > 0) {
            $duration = -$duration;
        }

        $this->setEffectValue($duration);

        return $this;
    }
}
