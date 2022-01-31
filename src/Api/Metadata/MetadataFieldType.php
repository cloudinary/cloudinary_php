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
 * Defines the structured metadata field type.
 *
 * @api
 */
class MetadataFieldType
{
    const STRING  = 'string';
    const INTEGER = 'integer';
    const DATE    = 'date';
    const ENUM    = 'enum';
    const SET     = 'set';
}
