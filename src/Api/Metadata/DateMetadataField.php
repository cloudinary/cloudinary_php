<?php

namespace Cloudinary\Metadata;

use Cloudinary\Utils;
use DateTime;

/**
 * Class DateMetadataField
 *
 * @package Cloudinary\Metadata
 */
class DateMetadataField extends MetadataField
{
    /**
     * DateMetadataField constructor
     *
     * @param string $label
     */
    public function __construct($label)
    {
        parent::__construct($label);
        $this->type = MetadataFieldType::DATE;
    }

    /**
     * @param DateTime $defaultValue
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = Utils::toISO8601DateOnly($defaultValue);
    }

    /**
     * @return DateTime|null
     * @throws \Exception
     */
    public function getDefaultValue()
    {
        return $this->defaultValue ? new DateTime($this->defaultValue) : null;
    }
}
