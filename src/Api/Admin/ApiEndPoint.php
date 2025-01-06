<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Api\Admin;

/**
 * Represents the root API endpoint.
 */
class ApiEndPoint
{
    public const PING               = 'ping';
    public const CONFIG             = 'config';
    public const USAGE              = 'usage';
    public const ASSETS             = 'resources';
    public const DERIVED_ASSETS     = 'derived_resources';
    public const RELATED_ASSETS     = 'related_assets';
    public const FOLDERS            = 'folders';
    public const TAGS               = 'tags';
    public const STREAMING_PROFILES = 'streaming_profiles';
    public const TRANSFORMATIONS    = 'transformations';
    public const UPLOAD_PRESETS     = 'upload_presets';
    public const UPLOAD_MAPPINGS    = 'upload_mappings';
    public const METADATA_FIELDS    = 'metadata_fields';
    public const ANALYSIS           = 'analysis';
}
