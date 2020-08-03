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

/**
 * Represents a structured metadata field with 'Int' type.
 *
 * @api
 */
class IntMetadataField extends MetadataField
{
    /**
     * The IntMetadataField constructor.
     *
     * @param string $label
     */
    public function __construct($label)
    {
        parent::__construct($label);
        $this->type = MetadataFieldType::INTEGER;
    }

    /**
     * Sets the default value for this field.
     *
     * @param int $defaultValue
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = (int)$defaultValue;
    }
}
