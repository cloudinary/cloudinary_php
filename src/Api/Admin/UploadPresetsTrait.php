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
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\ArrayUtils;

/**
 * Trait UploadPresetsTrait
 *
 * @property ApiClient $apiClient Defined in AdminApi class
 *
 * @api
 */
trait UploadPresetsTrait
{
    /**
     * Lists upload presets.
     *
     * @param array $options The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/admin_api#get_upload_presets
     */
    public function uploadPresets($options = [])
    {
        $uri    = [ApiEndPoint::UPLOAD_PRESETS];
        $params = ArrayUtils::whitelist($options, ['next_cursor', 'max_results']);

        return $this->apiClient->get($uri, $params);
    }

    /**
     * Retrieves the details of a single upload preset.
     *
     * @param string $name    The name of the upload preset.
     * @param array  $options The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/admin_api#get_the_details_of_a_single_upload_preset
     */
    public function uploadPreset($name, $options = [])
    {
        $uri = [ApiEndPoint::UPLOAD_PRESETS, $name];

        return $this->apiClient->get($uri, ArrayUtils::whitelist($options, ['max_results']));
    }

    /**
     * Deletes an upload preset.
     *
     * @param string $name The name of the upload preset.
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * @see https://cloudinary.com/documentation/admin_api#delete_an_upload_preset
     */
    public function deleteUploadPreset($name)
    {
        $uri = [ApiEndPoint::UPLOAD_PRESETS, $name];

        return $this->apiClient->delete($uri, []);
    }

    /**
     * Updates an upload preset.
     *
     * @param string $name    The name of the upload preset.
     *
     * @param array  $options The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * @see https://cloudinary.com/documentation/admin_api#update_an_upload_preset
     */
    public function updateUploadPreset($name, $options = [])
    {
        $uri    = [ApiEndPoint::UPLOAD_PRESETS, $name];
        $params = UploadApi::buildUploadParams($options);
        $params = array_merge($params, ArrayUtils::whitelist($options, ['unsigned', 'disallow_public_id', 'live']));

        return $this->apiClient->put($uri, $params);
    }

    /**
     * Creates a new upload preset.
     *
     * @param array $options The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/admin_api#create_an_upload_preset
     */
    public function createUploadPreset($options = [])
    {
        $params = UploadApi::buildUploadParams($options);
        $params = array_merge(
            $params,
            ArrayUtils::whitelist($options, ['name', 'unsigned', 'disallow_public_id', 'live'])
        );

        return $this->apiClient->postForm([ApiEndPoint::UPLOAD_PRESETS], $params);
    }
}
