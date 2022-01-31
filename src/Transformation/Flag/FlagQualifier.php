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
use Cloudinary\StringUtils;
use Cloudinary\Transformation\Qualifier\BaseQualifier;

/**
 * Class FlagQualifier
 */
class FlagQualifier extends BaseQualifier
{
    /**
     * @var string The flag qualifier key
     */
    protected static $key = 'fl';

    /**
     * @var string $flagName The name of the flag.
     */
    protected $flagName;

    /**
     * FlagQualifier constructor.
     *
     * @param string             $flagName The name of the flag.
     * @param string|array|mixed $value    An optional value of the flag.
     */
    public function __construct($flagName, $value = null)
    {
        parent::__construct();

        $this->setFlagName($flagName);
        $this->setQualifierValue($value);
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
     * @return FlagQualifier
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
        $flagQualifierName = $this->flagName ? self::getKey() . "_{$this->flagName}" : '';

        return ArrayUtils::implodeQualifierValues(
            $flagQualifierName,
            rawurlencode(StringUtils::encodeDot($this->value))
        );
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
