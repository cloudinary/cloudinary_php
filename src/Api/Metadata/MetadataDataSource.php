<?php

namespace Cloudinary\Metadata;

use InvalidArgumentException;

/**
 * Class MetadataDataSource
 *
 * @package Cloudinary\Metadata
 */
class MetadataDataSource extends Metadata
{
    /**
     * @var array MetadataDataEntry
     */
    protected $values;

    /**
     * MetadataDataSource constructor
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
     * @param array $values
     *
     * @throws InvalidArgumentException
     */
    public function setValues(array $values)
    {
        $this->values = [];
        foreach ($values as $entry) {
            if ($entry instanceof MetadataDataEntry) {
                $this->values[] = $entry;
            } elseif (is_array($entry) && isset($entry['value'])) {
                $this->values[] = new MetadataDataEntry(
                    $entry['value'],
                    isset($entry['external_id']) ? $entry['external_id'] : null
                );
            } else {
                throw new InvalidArgumentException('Variable $values is not valid data');
            }
        }
    }

    /**
     * @return array MetadataDataEntry
     */
    public function getValues()
    {
        return $this->values;
    }
}
