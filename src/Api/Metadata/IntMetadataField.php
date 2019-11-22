<?php

namespace Cloudinary\Metadata;

/**
 * Class IntMetadataField
 *
 * @package Cloudinary\Metadata
 */
class IntMetadataField extends MetadataField
{
    /**
     * IntMetadataField constructor
     *
     * @param string $label
     */
    public function __construct($label)
    {
        parent::__construct($label);
        $this->type = MetadataFieldType::INTEGER;
    }

    /**
     * @param int $defaultValue
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = (integer)$defaultValue;
    }
}
