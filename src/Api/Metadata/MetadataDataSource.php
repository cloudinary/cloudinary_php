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
 * Represents the data source (list values) of a structured metadata list field. This is used in both 'set' and 'enum'
 * field types. The datasource holds a list of the valid values to be used with the corresponding metadata field.
 *
 * @api
 */
class MetadataDataSource extends Metadata
{
    /**
     * @var MetadataDataEntry[]
     */
    protected $values;

    /**
     * The MetadataDataSource constructor.
     *
     * @param array $values
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $values)
    {
        $this->setValues($values);
    }

    /**
     * Gets the keys for all the properties of this object.
     *
     * @return array
     */
    public function getPropertyKeys()
    {
        return ['values'];
    }

    /**
     * Sets entities for this data source.
     *
     * @param MetadataDataEntry[]|array[] $values
     *
     * @throws InvalidArgumentException
     */
    public function setValues(array $values)
    {
        $this->values = [];
        foreach ($values as $value) {
            if ($value instanceof MetadataDataEntry) {
                $this->values[] = $value;
            } elseif (is_array($value) && isset($value['value'])) {
                $this->values[] = new MetadataDataEntry(
                    $value['value'],
                    isset($value['external_id']) ? $value['external_id'] : null
                );
            } else {
                throw new InvalidArgumentException('The specified metadata datasource values are not valid.');
            }
        }
    }

    /**
     * Gets entities of this data source.
     *
     * @return MetadataDataEntry[]
     */
    public function getValues()
    {
        return $this->values;
    }
}
