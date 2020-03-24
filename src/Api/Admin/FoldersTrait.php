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
 * Trait FoldersTrait
 *
 * Enables you to manage folders for your account.
 *
 * @property ApiClient $apiClient Defined in AdminApi class.
 *
 * @api
 */
trait FoldersTrait
{
    /**
     * Lists all root folders.
     *
     * @param array $options The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/admin_api#get_root_folders
     */
    public function rootFolders($options = [])
    {
        $params = ArrayUtils::whitelist($options, ['next_cursor', 'max_results']);

        return $this->apiClient->get(ApiEndPoint::FOLDERS, $params);
    }

    /**
     * Lists sub-folders.
     *
     * Lists the name and path of all the sub-folders of a given root folder. Limited to 2000 results.
     *
     * @param string $ofFolderPath The root folder
     * @param array  $options      The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * @see https://cloudinary.com/documentation/admin_api#get_subfolders
     */
    public function subFolders($ofFolderPath, $options = [])
    {
        $uri    = [ApiEndPoint::FOLDERS, $ofFolderPath];
        $params = ArrayUtils::whitelist($options, ['next_cursor', 'max_results']);

        return $this->apiClient->get($uri, $params);
    }

    /**
     * Creates a new empty folder.
     *
     * @param string $path The full path of the new folder to create.
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * @see https://cloudinary.com/documentation/admin_api#create_folder
     */
    public function createFolder($path)
    {
        $uri = [ApiEndPoint::FOLDERS, $path];

        return $this->apiClient->post($uri);
    }

    /**
     * Deletes an empty folder.
     *
     * Deleted folder must be empty, but can have descendant empty sub folders.
     *
     * @param string $path The full path of the empty folder to delete.
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * @see https://cloudinary.com/documentation/admin_api#delete_folder
     */
    public function deleteFolder($path)
    {
        $uri = [ApiEndPoint::FOLDERS, $path];

        return $this->apiClient->delete($uri);
    }
}
