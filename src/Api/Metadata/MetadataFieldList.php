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
 * Represents a structured metadata list field.
 *
 * @api
 */
abstract class MetadataFieldList extends MetadataField
{
    protected MetadataDataSource $datasource;
    protected bool $allowDynamicListValues;

    /**
     * The MetadataFieldList constructor.
     *
     * @param string                   $label      The label to assign.
     * @param array|MetadataDataSource $dataSource The data source.
     */
    public function __construct(string $label, array|MetadataDataSource $dataSource = [])
    {
        parent::__construct($label);
        $this->type = MetadataFieldType::STRING;
        $this->setDataSource($dataSource);
    }

    /**
     * Gets the keys for all the properties of this object.
     *
     * @return string[]
     */
    public function getPropertyKeys(): array
    {
        return array_merge(parent::getPropertyKeys(), ['datasource', 'allowDynamicListValues']);
    }

    /**
     * Gets the data source of this field.
     *
     * @return MetadataDataSource The data source.
     */
    public function getDataSource(): MetadataDataSource
    {
        return $this->datasource;
    }

    /**
     * Sets the data source for this field.
     *
     * @param array|MetadataDataSource $metadataDataSource The data source to set.
     *
     * @throws InvalidArgumentException
     */
    public function setDataSource(array|MetadataDataSource $metadataDataSource): void
    {
        if ($metadataDataSource instanceof MetadataDataSource) {
            $this->datasource = $metadataDataSource;
        } elseif (is_array($metadataDataSource)) {
            $this->datasource = new MetadataDataSource($metadataDataSource);
        } else {
            throw new InvalidArgumentException('The specified MetadataFieldList datasource is not valid.');
        }
    }

    /**
     * Gets the value indicating whether the field should allow dynamic list values.
     */
    public function isAllowDynamicListValues(): bool
    {
        return $this->allowDynamicListValues;
    }

    /**
     * Sets the value indicating whether the field should allow dynamic list values.
     *
     * @param bool $allowDynamicListValues The value to set.
     */
    public function setAllowDynamicListValues(bool $allowDynamicListValues = true): void
    {
        $this->allowDynamicListValues = $allowDynamicListValues;
    }
}
