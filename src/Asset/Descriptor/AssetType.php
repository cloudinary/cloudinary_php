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
    const KEY = 'resource_type';

    const IMAGE = 'image';
    const VIDEO = 'video';
    const RAW   = 'raw';
    const AUTO  = 'auto';
    const ALL   = 'all';
}
