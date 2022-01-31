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
 * Represents a structured metadata field with 'Enum' (single-selection list) type.
 *
 * @api
 */
class EnumMetadataField extends MetadataFieldList
{
    /**
     * The EnumMetadataField constructor.
     *
     * @param string                   $label
     * @param array|MetadataDataSource $dataSource
     */
    public function __construct($label, $dataSource = [])
    {
        parent::__construct($label, $dataSource);
        $this->type = MetadataFieldType::ENUM;
    }
}
