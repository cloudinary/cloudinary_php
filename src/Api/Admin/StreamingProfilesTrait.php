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
use Cloudinary\Api\ApiUtils;
use Cloudinary\Api\Exception\ApiError;
use Cloudinary\ArrayUtils;

/**
 * Trait StreamingProfilesTrait
 *
 * @property ApiClient $apiClient Defined in AdminApi class
 *
 * @api
 */
trait StreamingProfilesTrait
{
    /**
     * Lists streaming profiles.
     *
     * List streaming profiles associated with the current customer, including built-in and custom profiles.
     *
     * @return ApiResponse An array with a "data" key for results.
     *
     * @throws ApiError
     *
     * @see https://cloudinary.com/documentation/admin_api#get_adaptive_streaming_profiles
     */
    public function listStreamingProfiles()
    {
        return $this->apiClient->get(ApiEndPoint::STREAMING_PROFILES);
    }

    /**
     * Gets details of a single streaming profile.
     *
     * Retrieve the details of a single streaming profile by name.
     *
     * @param string $name The identification name of the streaming profile
     *
     * @return ApiResponse An array with a "data" key for results.
     *
     * @throws ApiError
     *
     * @see https://cloudinary.com/documentation/admin_api#get_details_of_a_single_streaming_profile
     */
    public function getStreamingProfile($name)
    {
        $uri = [ApiEndPoint::STREAMING_PROFILES, $name];

        return $this->apiClient->get($uri);
    }

    /**
     * Deletes or reverts the specified streaming profile.
     *
     * For custom streaming profiles, delete the specified profile.
     * For built-in streaming profiles, if the built-in profile was modified, revert the profile to the original
     * settings.
     * For built-in streaming profiles that have not been modified, the Delete method returns an error.
     *
     * @param string $name The identification name of the streaming profile.
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * @see https://cloudinary.com/documentation/admin_api#delete_or_revert_the_specified_streaming_profile
     */
    public function deleteStreamingProfile($name)
    {
        $uri = [ApiEndPoint::STREAMING_PROFILES, $name];

        return $this->apiClient->delete($uri);
    }

    /**
     * Updates an existing streaming profile.
     *
     * Update the specified existing streaming profile. You can update both custom and built-in profiles.
     * The specified list of representations replaces the previous list.
     *
     * @param string $name    The identification name of the streaming profile.
     * @param array  $options The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * @see https://cloudinary.com/documentation/admin_api#create_a_streaming_profile
     */
    public function updateStreamingProfile($name, $options = [])
    {
        $uri    = [ApiEndPoint::STREAMING_PROFILES, $name];
        $params = $this->prepareStreamingProfileParams($options);

        return $this->apiClient->put($uri, $params);
    }

    /**
     * Creates a new, custom streaming profile.
     *
     * @param string $name    The identification name to assign to the new streaming profile.
     *                        The name is case-insensitive and can contain alphanumeric characters, underscores (_) and
     *                        hyphens (-). If the name is of a predefined profile, the profile will be modified.
     * @param array  $options The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * @see self::createStreamingProfile()
     * @see https://cloudinary.com/documentation/admin_api#create_a_streaming_profile
     */
    public function createStreamingProfile($name, $options = [])
    {
        $uri = [ApiEndPoint::STREAMING_PROFILES];

        $params         = $this->prepareStreamingProfileParams($options);
        $params['name'] = $name;

        return $this->apiClient->postForm($uri, $params);
    }

    /**
     * Prepares streaming profile parameters for API calls.
     *
     * @param array $options The optional parameters. See the admin API documentation.
     *
     * @return array A single profile parameters.
     *
     * @internal
     */
    protected function prepareStreamingProfileParams($options)
    {
        $params = ArrayUtils::whitelist($options, ['display_name']);

        if (isset($options['representations'])) {
            $representations           = array_map(
                static function ($representation) {
                    return ['transformation' => ApiUtils::serializeAssetTransformations($representation)];
                },
                $options['representations']
            );
            $params['representations'] = json_encode($representations);
        }

        return $params;
    }
}
