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

use Cloudinary\ArrayUtils;
use Cloudinary\Transformation\Parameter\BaseParameter;

/**
 * Class FlagParameter
 */
class FlagParameter extends BaseParameter
{
    /**
     * @var string The flag parameter key
     */
    protected static $key = 'fl';

    /**
     * @var string $flagName The name of the flag.
     */
    protected $flagName;

    /**
     * FlagParameter constructor.
     *
     * @param string             $flagName The name of the flag.
     * @param string|array|mixed $value    An optional value of the flag.
     */
    public function __construct($flagName, $value = null)
    {
        parent::__construct();

        $this->setFlagName($flagName);
        $this->setParamValue($value);
    }

    /**
     * Gets the name of the flag.
     *
     * @return string
     *
     * @internal
     */
    public function getFlagName()
    {
        return $this->flagName;
    }

    /**
     * Sets the name of the flag.
     *
     * @param string $flagName The name.
     *
     * @return FlagParameter
     */
    public function setFlagName($flagName)
    {
        $this->flagName = $flagName;

        return $this;
    }


    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        $flagParamName = $this->flagName ? self::getKey() . "_{$this->flagName}" : '';

        return ArrayUtils::implodeParamValues($flagParamName, $this->value);
    }

    /**
     * Serializes to json.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        $res = [];
        if ($this->flagName) {
            $res[self::getName()] = $this->flagName;
        }
        if ($this->value) {
            $res['value'] = $this->value;
        }

        return $res;
    }
}
