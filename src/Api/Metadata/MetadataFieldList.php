<?php

namespace Cloudinary\Metadata;

use InvalidArgumentException;

/**
 * Class MetadataFieldList
 *
 * @package Cloudinary\Metadata
 */
abstract class MetadataFieldList extends MetadataField
{
    /**
     * $var array
     */
    protected $datasource;

    /**
     * StringMetadataField constructor
     *
     * @param string $label
     * @param array|MetadataDataSource $dataSource
     */
    public function __construct($label, $dataSource = [])
    {
        parent::__construct($label);
        $this->type = MetadataFieldType::STRING;
        $this->setDatasource($dataSource);
    }

    /**
     * @return array
     */
    public function getDataSource()
    {
        return $this->datasource;
    }

    /**
     * @param array|MetadataDataSource $metadataDataSource
     *
     * @throws InvalidArgumentException
     */
    public function setDataSource($metadataDataSource)
    {
        if ($metadataDataSource instanceof MetadataDataSource) {
            $this->datasource = $metadataDataSource;
        } elseif (is_array($metadataDataSource)) {
            $this->datasource = new MetadataDataSource($metadataDataSource);
        } else {
            throw new InvalidArgumentException('$metadataDataSource is not valid data');
        }
    }
}
