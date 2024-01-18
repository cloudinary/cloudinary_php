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
    const PING               = 'ping';
    const USAGE              = 'usage';
    const ASSETS             = 'resources';
    const DERIVED_ASSETS     = 'derived_resources';
    const RELATED_ASSETS     = 'related_assets';
    const FOLDERS            = 'folders';
    const TAGS               = 'tags';
    const STREAMING_PROFILES = 'streaming_profiles';
    const TRANSFORMATIONS    = 'transformations';
    const UPLOAD_PRESETS     = 'upload_presets';
    const UPLOAD_MAPPINGS    = 'upload_mappings';
    const METADATA_FIELDS    = 'metadata_fields';
    const ANALYSIS           = 'analysis';
}
