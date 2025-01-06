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
 * Class File
 *
 * @api
 */
class File extends BaseAsset
{
    /**
     * @var string $assetType The type of the asset.
     */
    protected static string $assetType = 'raw';

    /**
     * @var array A list of the delivery types that support SEO suffix.
     */
    protected static array $suffixSupportedDeliveryTypes = [
        AssetType::RAW => [DeliveryType::UPLOAD => 'files'],
    ];
}
