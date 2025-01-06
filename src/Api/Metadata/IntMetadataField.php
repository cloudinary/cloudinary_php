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
     */
    public function __construct(string $label)
    {
        parent::__construct($label);
        $this->type = MetadataFieldType::INTEGER;
    }

    /**
     * Sets the default value for this field.
     *
     */
    public function setDefaultValue(mixed $defaultValue): void
    {
        $this->defaultValue = (int)$defaultValue;
    }
}
