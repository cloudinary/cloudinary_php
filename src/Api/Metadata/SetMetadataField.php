<?php

namespace Cloudinary\Metadata;

/**
 * Class SetMetadataField
 *
 * @package Cloudinary\Metadata
 */
class SetMetadataField extends MetadataFieldList
{
    /**
     * SetMetadataField constructor
     *
     * @param string $label
     * @param array|MetadataDataSource $dataSource
     */
    public function __construct($label, $dataSource = [])
    {
        parent::__construct($label, $dataSource);
        $this->type = MetadataFieldType::SET;
    }
}
