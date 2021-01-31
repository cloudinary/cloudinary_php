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

use Cloudinary\Utils;
use DateTime;
use Exception;

/**
 * Represents a structured metadata field with 'Date' type.
 *
 * @api
 */
class DateMetadataField extends MetadataField
{
    /**
     * The DateMetadataField constructor.
     *
     * @param string $label
     */
    public function __construct($label)
    {
        parent::__construct($label);
        $this->type = MetadataFieldType::DATE;
    }

    /**
     * Sets the default date for this field.
     *
     * @param DateTime $defaultValue The date to set.
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = Utils::toISO8601DateOnly($defaultValue);
    }

    /**
     * Gets the default date of this field.
     *
     * @return DateTime|null
     * @throws Exception When the underlying value is malformed.
     */
    public function getDefaultValue()
    {
        return $this->defaultValue ? new DateTime($this->defaultValue) : null;
    }
}
