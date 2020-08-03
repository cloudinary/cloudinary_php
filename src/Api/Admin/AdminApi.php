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

use Cloudinary\Api\ApiClient;

/**
 * Enables Cloudinary Admin API functionality.
 *
 * **Learn more**: <a
 * href=https://cloudinary.com/documentation/admin_api target="_blank">
 * Admin API Reference</a>
 *
 * @api
 */
class AdminApi
{
    use AssetsTrait;
    use FoldersTrait;
    use TransformationsTrait;
    use StreamingProfilesTrait;
    use UploadPresetsTrait;
    use UploadMappingsTrait;
    use MiscTrait;
    use MetadataFieldsTrait;

    /**
     * @var ApiClient $apiClient The API client instance.
     */
    protected $apiClient;

    /**
     * AdminApi constructor.
     *
     * @param mixed $configuration
     *
     * @noinspection UnusedConstructorDependenciesInspection*/
    public function __construct($configuration = null)
    {
        $this->apiClient = new ApiClient($configuration);
    }
}
