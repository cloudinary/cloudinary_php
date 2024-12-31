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
 * Class AssetType
 *
 * @api
 */
abstract class AssetType
{
    public const KEY = 'resource_type';

    public const IMAGE = 'image';
    public const VIDEO = 'video';
    public const RAW   = 'raw';
    public const AUTO = 'auto';
    public const ALL  = 'all';
}
