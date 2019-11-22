<?php

namespace Cloudinary\Metadata;

/**
 * Class StringMetadataField
 *
 * @package Cloudinary\Metadata
 */
class StringMetadataField extends MetadataField
{
    /**
     * StringMetadataField constructor
     *
     * @param string $label
     */
    public function __construct($label)
    {
        parent::__construct($label);
        $this->type = MetadataFieldType::STRING;
    }

    /**
     * @param string $defaultValue
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = (string)$defaultValue;
    }
}
