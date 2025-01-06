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
    public const STRING = 'string';
    public const INTEGER = 'integer';
    public const DATE    = 'date';
    public const ENUM = 'enum';
    public const SET  = 'set';
}
