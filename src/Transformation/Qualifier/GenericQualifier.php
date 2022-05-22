<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Qualifier;

use Cloudinary\ArrayUtils;

/**
 * Class GenericQualifier
 */
class GenericQualifier extends BaseQualifier
{
    /**
     * @var string $genericKey User provided generic key instead of static class key
     */
    protected $genericKey;

    /**
     * GenericQualifier constructor.
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
     * Sets the generic qualifier key.
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
     * Gets qualifier full name.
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
        $value = (string)$this->value;

        return $value === '' ? '' : $this->genericKey . static::KEY_VALUE_DELIMITER . $value;
    }
}
