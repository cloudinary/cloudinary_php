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
 * Class CloudinaryParams
 *
 * @package Cloudinary\Tag
 */
abstract class AssetParams
{
    /**
     * @var array ASSET_KEYS A list of keys used by the fromParams() function
     *
     * @internal
     */
    const ASSET_KEYS = [
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
        'secure_distribution',
        'shorten',
        'sign_url',
        'ssl_detected',
        'type',
        'url_suffix',
        'use_root_path',
        'version',
    ];
}
