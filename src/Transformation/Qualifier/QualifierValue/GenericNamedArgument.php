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

/**
 * Class GenericNamedArgument
 */
class GenericNamedArgument extends BaseNamedArgument
{
    /**
     * GenericNamedArgument constructor.
     *
     * @param        $name
     * @param        $value
     * @param string $nameValueDelimiter
     * @param string $innerValueDelimiter
     */
    public function __construct(
        $name,
        $value,
        $nameValueDelimiter = self::ARG_NAME_VALUE_DELIMITER,
        $innerValueDelimiter = self::ARG_INNER_VALUE_DELIMITER
    ) {
        parent::__construct();

        $this->argName = $name;
        $this->setValue($value);
        $this->nameValueDelimiter  = $nameValueDelimiter;
        $this->innerValueDelimiter = $innerValueDelimiter;
    }

    /**
     * Sets the named argument value.
     *
     * @param $value
     *
     * @return static
     */
    public function setValue($value)
    {
        $this->argMultiValue = $value;

        return $this;
    }
}
