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
use Cloudinary\Transformation\Expression\UVal;
use Cloudinary\Transformation\Qualifier\GenericQualifier;
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
class Variable extends GenericQualifier
{
    const VALUE_CLASS = VariableValue::class;
    const AS_FLOAT   = 'to_f';
    const AS_INTEGER = 'to_i';

    /**
     * Defines a new user variable with the given value.
     *
     * @param string $name  The name of the variable.
     * @param mixed  $value The value of the variable.
     *
     * @return Variable
     */
    public static function set($name, $value)
    {
        return new self($name, $value);
    }

    /**
     * Defines a new user variable with the given asset public id.
     *
     * @param string $name     The name of the variable.
     * @param mixed  $publicId The referenced asset public id.
     *
     * @return Variable
     */
    public static function setAssetReference($name, $publicId)
    {
        return new self($name, UVal::assetReference($publicId));
    }

    /**
     * Defines a new user variable with the given context key.
     *
     * @param string $name       The name of the variable.
     * @param mixed  $contextKey The context key.
     *
     * @return Variable
     */
    public static function setFromContext($name, $contextKey)
    {
        return new self($name, UVal::context($contextKey));
    }

    /**
     * Defines a new user variable with the given structured metadata key.
     *
     * @param string $name        The name of the variable.
     * @param mixed  $metadataKey The metadata key.
     *
     * @return Variable
     */
    public static function setFromMetadata($name, $metadataKey)
    {
        return new self($name, UVal::metadata($metadataKey));
    }

    /**
     * Indicates Cloudinary to treat the value as float.
     *
     * @param bool $asFloat Whether to treat as float.
     *
     * @return $this
     */
    public function asFloat($asFloat = true)
    {
        if ($asFloat) {
            $this->value->addValues(self::AS_FLOAT);
        }

        return $this;
    }

    /**
     * Indicates Cloudinary to treat the value as integer.
     *
     * @param bool $asInteger Whether to treat as integer.
     *
     * @return $this
     */
    public function asInteger($asInteger = true)
    {
        if ($asInteger) {
            $this->value->addValues(self::AS_INTEGER);
        }

        return $this;
    }

    /**
     * Sets the variable name as the qualifier key.
     *
     * @param string $name The name of the variable.
     *
     * @return Variable
     */
    public function setKey($name)
    {
        $name = StringUtils::ensureStartsWith($name, '$');

        if (empty($name) || ! self::isVariable($name)) {
            throw new InvalidArgumentException('Invalid variable name');
        }

        parent::setKey($name);

        return $this;
    }

    /**
     * Sets ((re)initializes) the qualifier value.
     *
     * @param $value
     *
     * @return static
     */
    public function setQualifierValue(...$value)
    {
        if (count($value) === 1) {
            if (is_string($value[0])) {
                $value[0] = UVal::string($value[0]);
            } elseif (is_array($value[0])) {
                $value[0] = UVal::stringArray($value[0]);
            }
        }

        parent::setQualifierValue(...$value);

        return $this;
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
