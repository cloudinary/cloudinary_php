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
use Cloudinary\Api\ApiResponse;
use Cloudinary\Api\Exception\ApiError;
use Cloudinary\ArrayUtils;

/**
 * Enables you to manage the upload mappings.
 * **Learn more**: <a
 * href=https://cloudinary.com/documentation/admin_api#upload_mappings target="_blank">
 * Upload Mappings method - Admin API</a>
 *
 * @property ApiClient $apiClient Defined in AdminApi class
 *
 * @api
 */
trait UploadMappingsTrait
{
    /**
     * Lists upload mappings by folder and its mapped template (URL).
     *
     *
     * @param array $options The optional parameters. See the
     * <a href=https://cloudinary.com/documentation/admin_api#get_upload_mapping target="_blank"> Admin API</a>
     * documentation.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/admin_api#get_upload_mappings
     */
    public function uploadMappings($options = [])
    {
        $uri    = ApiEndPoint::UPLOAD_MAPPINGS;
        $params = ArrayUtils::whitelist($options, ['next_cursor', 'max_results']);

        return $this->apiClient->get($uri, $params);
    }

    /**
     * Returns the details of the specified upload mapping.
     *
     * Retrieve the mapped template (URL) of a specified upload mapping folder.
     *
     * @param string $name The name of the upload mapping folder.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/admin_api#get_the_details_of_a_single_upload_mapping
     */
    public function uploadMapping($name)
    {
        $uri    = ApiEndPoint::UPLOAD_MAPPINGS;
        $params = ['folder' => $name];

        return $this->apiClient->get($uri, $params);
    }

    /**
     * Deletes an upload mapping.
     *
     *
     * @param string $name The name of the upload mapping folder to delete.
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * @see https://cloudinary.com/documentation/admin_api#delete_an_upload_mapping
     */
    public function deleteUploadMapping($name)
    {
        $uri    = ApiEndPoint::UPLOAD_MAPPINGS;
        $params = ['folder' => $name];

        return $this->apiClient->delete($uri, $params);
    }

    /**
     * Updates an existing upload mapping with a new template (URL).
     *
     *
     * @param string $name    The name of the upload mapping folder to remap.
     * @param array  $options The optional parameters. See the
     * <a href=https://cloudinary.com/documentation/admin_api#update_an_upload_mapping target="_blank"> Admin API</a>
     * documentation.
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * @see https://cloudinary.com/documentation/admin_api#update_an_upload_mapping
     */
    public function updateUploadMapping($name, $options = [])
    {
        $uri    = ApiEndPoint::UPLOAD_MAPPINGS;
        $params = array_merge(['folder' => $name], ArrayUtils::whitelist($options, ['template']));

        return $this->apiClient->put($uri, $params);
    }

    /**
     * Creates a new upload mapping.
     *
     *
     * @param string $name    The name of the folder to map.
     * @param array  $options The optional parameters. See the
     * <a href=https://cloudinary.com/documentation/admin_api#create_an_upload_mapping target="_blank"> Admin API</a>
     * documentation.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/admin_api#create_an_upload_mapping
     */
    public function createUploadMapping($name, $options = [])
    {
        $uri    = ApiEndPoint::UPLOAD_MAPPINGS;
        $params = array_merge(['folder' => $name], ArrayUtils::whitelist($options, ['template']));

        return $this->apiClient->postForm($uri, $params);
    }
}
