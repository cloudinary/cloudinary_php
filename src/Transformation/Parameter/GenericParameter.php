<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Parameter;

use Cloudinary\ArrayUtils;
use Cloudinary\Transformation\Expression\ExpressionUtils;

/**
 * Class GenericParameter
 */
class GenericParameter extends BaseParameter
{
    /**
     * @var string $genericKey User provided generic key instead of static class key
     */
    protected $genericKey;

    /**
     * GenericParameter constructor.
     *
     * @param       $genericKey
     * @param mixed ...$value
     */
    public function __construct($genericKey, ...$value)
    {
        $this->setKey($genericKey);

        parent::__construct(...$value);
    }

    /**
     * Sets the generic parameter key.
     *
     * @param string $genericKey The key.
     *
     * @return $this
     */
    public function setKey($genericKey)
    {
        $this->genericKey = $genericKey;

        return $this;
    }

    /**
     * Gets parameter full name.
     *
     * @return string
     */
    public function getFullName()
    {
        return ArrayUtils::implodeFiltered('_', [parent::getFullName(), $this->genericKey]);
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        $value = ExpressionUtils::normalize((string)$this->value);

        return $value === '' ? '' : $this->genericKey . static::KEY_VALUE_DELIMITER . $value;
    }
}
