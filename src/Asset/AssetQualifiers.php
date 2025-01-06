<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Asset;

/**
 * Class AssetQualifiers
 *
 * @package Cloudinary\Tag
 */
abstract class AssetQualifiers
{
    /**
     * @var array ASSET_KEYS A list of keys used by the fromParams() function
     *
     * @internal
     */
    public const ASSET_KEYS
        = [
            'api_secret',
            'auth_token',
            'cdn_subdomain',
            'cloud_name',
            'cname',
            'format',
            'long_url_signature',
            'private_cdn',
            'resource_type',
            'secure',
            'secure_cdn_subdomain',
            'secure_distribution', // deprecated, still consume for backwards compatibility
            'secure_cname',
            'shorten',
            'sign_url',
            'ssl_detected',
            'type',
            'url_suffix',
            'use_root_path',
            'version',
            'responsive',
            'responsive_width',
            'hidpi',
            'client_hints',
        ];
}
