<?php

namespace Cloudinary\Metadata;

use InvalidArgumentException;

/**
 * Class MetadataDataEntry
 *
 * @package Cloudinary\Metadata
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
     * MetadataDataEntry constructor
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
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        if (is_null($value)) {
            throw new InvalidArgumentException('Value is not valid');
        }
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getExternalId()
    {
        return $this->externalId;
    }

    /**
     * @param string $externalId
     */
    public function setExternalId($externalId)
    {
        $this->externalId = $externalId;
    }
}
