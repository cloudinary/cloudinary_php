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

use OutOfRangeException;

/**
 * Class LimitedEffectQualifier
 *
 * This class is used for all effects that have a limited range of the value.
 *
 * @internal
 */
class LimitedEffectQualifier extends ValueEffectQualifier
{
    /**
     * @var array $range The validation range.
     */
    protected $range;

    /**
     * LimitedEffectQualifier constructor.
     *
     * @param       $effectName
     * @param       $range
     * @param       $level
     * @param array $args
     */
    public function __construct($effectName, $range, $level = null, ...$args)
    {
        parent::__construct($effectName);

        $this->setValidRange($range);
        $this->setEffectValue($level);

        $this->value->addValues(...$args);
    }

    /**
     * Sets a range for validation.
     *
     * @param array $range The range including min and max values.
     *
     * @return $this
     *
     * @internal
     */
    public function setValidRange($range)
    {
        $this->range = $range;

        return $this;
    }

    /**
     * Internal setter of the effect value.
     *
     * @param mixed $value The value to set.
     *
     * @return LimitedEffectQualifier
     *
     * @internal
     */
    public function setEffectValue($value)
    {
        if (is_numeric($value) && ! empty($this->range) && ($value < $this->range[0] || $value > $this->range[1])) {
            throw new OutOfRangeException("Value must be in range: [{$this->range[0]}, {$this->range[1]}]");
        }

        parent::setEffectValue($value);

        return $this;
    }
}
