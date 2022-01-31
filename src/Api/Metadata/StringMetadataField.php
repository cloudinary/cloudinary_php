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
 * Represents a metadata field with 'String' type.
 *
 * @api
 */
class StringMetadataField extends MetadataField
{
    /**
     * The StringMetadataField constructor.
     *
     * @param string $label
     */
    public function __construct($label)
    {
        parent::__construct($label);
        $this->type = MetadataFieldType::STRING;
    }

    /**
     * Sets the default value.
     *
     * @param string $defaultValue
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = (string)$defaultValue;
    }
}
