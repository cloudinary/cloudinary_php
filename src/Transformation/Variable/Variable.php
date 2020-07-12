<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Variable;

use Cloudinary\StringUtils;
use Cloudinary\Transformation\Parameter\GenericParameter;
use InvalidArgumentException;

/**
 * Defines methods for using variables.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/user_defined_variables" target="_blank">
 * User defined variables</a>
 *
 *
 * @api
 */
class Variable extends GenericParameter
{
    /**
     * Defines a new user variable with the given value.
     *
     * @param string $name  Variable name
     * @param mixed  $value Variable value
     *
     * @return Variable
     */
    public static function define($name, $value)
    {
        return new self($name, $value);
    }

    /**
     * Sets the variable name as the parameter key.
     *
     * @param string $name The name of the variable.
     */
    public function setKey($name)
    {
        if (! StringUtils::startsWith($name, '$')) {
            $name = "\${$name}";
        }

        if (empty($name) || ! self::isVariable($name)) {
            throw new InvalidArgumentException('Invalid variable name');
        }

        parent::setKey($name);
    }

    /**
     * Returns the variable name.
     *
     * @return string
     */
    public function getVariableName()
    {
        return $this->genericKey;
    }

    /**
     * Determines whether the candidate is a valid variable name.
     *
     * @param string $candidate Variable name candidate.
     *
     * @return bool
     */
    public static function isVariable($candidate)
    {
        return (boolean)preg_match('/^\$[a-zA-Z]\w*$/', $candidate);
    }

    /**
     * Serializes to json.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        /** @noinspection IsEmptyFunctionUsageInspection */
        if (empty($this->getValue())) {
            return [];
        }

        return ['variable' => ['name' => $this->getVariableName(), 'value' => $this->getValue()]];
    }
}
