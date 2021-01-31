<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Argument;

use OutOfRangeException;

/**
 * Class GenericNamedArgument
 */
class LimitedGenericNamedArgument extends GenericNamedArgument
{
    protected $range = null;

    /**
     * GenericNamedArgument constructor.
     *
     * @param string $name  The name of the argument.
     * @param mixed  $value The value of the argument.
     * @param array  $range The range including min and max values.
     * @param string $nameValueDelimiter
     * @param string $innerValueDelimiter
     */
    public function __construct(
        $name,
        $value,
        $range = null,
        $nameValueDelimiter = self::ARG_NAME_VALUE_DELIMITER,
        $innerValueDelimiter = self::ARG_INNER_VALUE_DELIMITER
    ) {
        $this->setValidRange($range);

        parent::__construct($name, $value, $nameValueDelimiter, $innerValueDelimiter);
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
     * Internal setter of the value.
     *
     * @param mixed $value The value to set.
     *
     * @return $this
     *
     * @internal
     */
    public function setValue($value)
    {
        if (is_numeric($value) && ! empty($this->range) && ($value < $this->range[0] || $value > $this->range[1])) {
            throw new OutOfRangeException("Value must be in range: [{$this->range[0]}, {$this->range[1]}]");
        }

        parent::setValue($value);

        return $this;
    }
}
