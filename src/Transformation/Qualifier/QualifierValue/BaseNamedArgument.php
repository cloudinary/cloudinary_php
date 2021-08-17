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

use Cloudinary\ArrayUtils;
use Cloudinary\Transformation\BaseComponent;

/**
 * Class BaseNamedArgument
 */
abstract class BaseNamedArgument extends BaseComponent
{
    const ARG_NAME_VALUE_DELIMITER  = '_';
    const ARG_INNER_VALUE_DELIMITER = ':';

    /**
     * @var string $argName The name of the argument.
     */
    protected $argName;

    /**
     * @var array $argMultiValue The value of the argument.
     */
    protected $argMultiValue = [];

    /**
     * @var string $nameValueDelimiter Run-time settable name-value delimiter.
     */
    protected $nameValueDelimiter;

    /**
     * @var string $innerValueDelimiter Run-time settable inner value delimiter.
     */
    protected $innerValueDelimiter;

    /**
     * BaseNamedArgument constructor.
     *
     * @param $value
     */
    public function __construct(...$value)
    {
        parent::__construct();

        $this->addValues(...$value);
    }


    /**
     * @param mixed ...$value
     *
     * @return $this
     */
    public function addValues(...$values)
    {
        $this->argMultiValue = array_merge($this->argMultiValue, $values);

        return $this;
    }

    /**
     * Gets the multi value of the argument.
     *
     * @return array
     */
    public function getMultiValue()
    {
        return $this->argMultiValue;
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        return ArrayUtils::implodeFiltered(
            $this->nameValueDelimiter ?: static::ARG_NAME_VALUE_DELIMITER,
            [
                $this->getArgName(),
                ArrayUtils::implodeFiltered(
                    $this->innerValueDelimiter ?: static::ARG_INNER_VALUE_DELIMITER,
                    ArrayUtils::build(ArrayUtils::flatten($this->argMultiValue))
                ),
            ]
        );
    }

    /**
     * Serializes to json.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return ['name' => $this->getArgName(), 'value' => ArrayUtils::flatten($this->argMultiValue)];
    }

    /**
     * Gets the argument name.
     *
     * @return string
     *
     * @internal
     */

    protected function getArgName()
    {
        return $this->argName ?: $this->getFullName();
    }
}
