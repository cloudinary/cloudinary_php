<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Api\Metadata;

use InvalidArgumentException;

/**
 * Controls structured metadata data entry.
 *
 * @api
 */
class MetadataDataEntry extends Metadata
{
    /**
     * @var string
     */
    protected $externalId;

    /**
     * @var string
     */
    protected $value;

    /**
     * MetadataDataEntry constructor.
     *
     * @param string $value
     * @param string $externalId
     */
    public function __construct($value, $externalId = null)
    {
        $this->setValue($value);
        $this->setExternalId($externalId);
    }

    /**
     * Gets the keys for all the properties of this object.
     *
     * @return array
     */
    public function getPropertyKeys()
    {
        return ['externalId', 'value'];
    }

    /**
     * Gets the value of the entry.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value of the entry.
     *
     * @param string $value
     */
    public function setValue($value)
    {
        if (is_null($value)) {
            throw new InvalidArgumentException('Metadata data entry value is not valid');
        }
        $this->value = $value;
    }

    /**
     * Gets the ID of the entry.
     *
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * Sets the ID of the entry. Will be auto-generated if left blank.
     *
     * @param string $externalId
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }
}
