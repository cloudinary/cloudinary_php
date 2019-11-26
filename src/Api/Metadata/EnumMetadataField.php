<?php

namespace Cloudinary\Metadata;

/**
 * Class EnumMetadataField
 *
 * @package Cloudinary\Metadata
 */
class EnumMetadataField extends MetadataFieldList
{
    /**
     * EnumMetadataField constructor
     *
     * @param string $label
     * @param array|MetadataDataSource $dataSource
     */
    public function __construct($label, $dataSource = [])
    {
        parent::__construct($label, $dataSource);
        $this->type = MetadataFieldType::ENUM;
    }
}
