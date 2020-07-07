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
use Cloudinary\Asset\AssetType;
use Cloudinary\Asset\DeliveryType;
use Cloudinary\Asset\ModerationStatus;

/**
 * Trait AssetsTrait
 *
 * @property ApiClient $apiClient Defined in AdminApi class.
 *
 * @api
 */
trait AssetsTrait
{
    /**
     * Lists available resource types.
     *
     * @return ApiResponse
     */
    public function resourceTypes()
    {
        return $this->apiClient->get(ApiEndPoint::RESOURCES);
    }

    /**
     * Lists all uploaded resources optionally filtered by the specified options.
     *
     * @param array $options The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/admin_api#get_resources
     */
    public function resources($options = [])
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $uri       = [ApiEndPoint::RESOURCES, $assetType];
        ArrayUtils::appendNonEmpty($uri, ArrayUtils::get($options, DeliveryType::KEY));

        $params = ArrayUtils::whitelist(
            $options,
            [
                'next_cursor',
                'max_results',
                'prefix',
                'tags',
                'context',
                'moderations',
                'direction',
                'start_at',
            ]
        );

        return $this->apiClient->get($uri, $params);
    }

    /**
     * Lists resources by tag.
     *
     * Retrieve a list of resources with a specified tag.
     * This method does not return deleted resources even if they have been backed up.
     *
     * @param string $tag     The tag name of the resources.
     * @param array  $options The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/admin_api#get_resources_by_tag
     */
    public function resourcesByTag($tag, $options = [])
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $uri       = [ApiEndPoint::RESOURCES, $assetType, 'tags', $tag];
        $params    = ArrayUtils::whitelist(
            $options,
            ['next_cursor', 'max_results', 'tags', 'context', 'moderations', 'direction']
        );

        return $this->apiClient->get($uri, $params);
    }

    /**
     * Lists resources by context.
     *
     * Retrieve a list of resources with a specified context key.
     * This method does not return deleted resources even if they have been backed up.
     *
     * @param string $key     Only resources with this context key are returned.
     * @param string $value   Only resources with this value for the context key are returned.
     *                        If this parameter is not provided, all resources with the given context key are returned,
     *                        regardless of the actual value of the key.
     * @param array  $options The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/admin_api#get_resources_by_context
     */
    public function resourcesByContext($key, $value = null, $options = [])
    {
        $assetType       = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $uri             = [ApiEndPoint::RESOURCES, $assetType, 'context'];
        $params          = ArrayUtils::whitelist(
            $options,
            ['next_cursor', 'max_results', 'tags', 'context', 'moderations', 'direction']
        );
        $params['key']   = $key;
        $params['value'] = $value;

        return $this->apiClient->get($uri, $params);
    }

    /**
     * Lists resources in moderation queues.
     *
     * @param string $kind    Type of image moderation queue to list.
     *                        Valid values:  "manual", "webpurify", "aws_rek", or "metascan".
     * @param string $status  Moderation status of resources.
     *                        Valid values: "pending", "approved", "rejected".
     * @param array  $options The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/admin_api#get_resources_in_moderation_queues
     */
    public function resourcesByModeration($kind, $status, $options = [])
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $uri       = [ApiEndPoint::RESOURCES, $assetType, 'moderations', $kind, $status];

        $params = ArrayUtils::whitelist(
            $options,
            ['next_cursor', 'max_results', 'tags', 'context', 'moderations', 'direction']
        );

        return $this->apiClient->get($uri, $params);
    }

    /**
     * Lists resources by public IDs.
     *
     * @param string|array $publicIds List resources with the given public IDs (up to 100).
     * @param array        $options   The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/admin_api#get_resources
     */
    public function resourcesByIds($publicIds, $options = [])
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $type      = ArrayUtils::get($options, DeliveryType::KEY, DeliveryType::UPLOAD);
        $uri       = [ApiEndPoint::RESOURCES, $assetType, $type];

        $params               = ArrayUtils::whitelist($options, ['public_ids', 'tags', 'moderations', 'context']);
        $params['public_ids'] = $publicIds;

        return $this->apiClient->get($uri, $params);
    }

    /**
     * Details of a single resource.
     *
     * Return details of the requested resource as well as all its derived resources.
     * Note that if you only need details about the original resource,
     * you can also use the Uploader::upload or Uploader::explicit methods, which are not rate limited.
     *
     * @param string $publicId The public ID of the resource.
     * @param array  $options  The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/admin_api#get_the_details_of_a_single_resource
     */
    public function resource($publicId, $options = [])
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $type      = ArrayUtils::get($options, DeliveryType::KEY, DeliveryType::UPLOAD);
        $uri       = [ApiEndPoint::RESOURCES, $assetType, $type, $publicId];

        $params = ArrayUtils::whitelist(
            $options,
            [
                'exif',
                'colors',
                'faces',
                'quality_analysis',
                'image_metadata',
                'phash',
                'pages',
                'coordinates',
                'max_results',
                'accessibility_analysis',
            ]
        );

        return $this->apiClient->get($uri, $params);
    }

    /**
     * Restores a deleted resource.
     *
     * Reverts to the latest backed up version of the resource.
     *
     * @param string|array $publicIds The public IDs of (deleted or existing) backed up resources to restore.
     * @param array        $options   The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/admin_api#restore_resources
     */
    public function restore($publicIds, $options = [])
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $type      = ArrayUtils::get($options, DeliveryType::KEY, DeliveryType::UPLOAD);
        $uri       = [ApiEndPoint::RESOURCES, $assetType, $type, 'restore'];

        $params = array_merge($options, ['public_ids' => $publicIds]);

        return $this->apiClient->postForm($uri, $params);
    }

    /**
     * Updates details of an existing resource.
     *
     * Update one or more of the attributes associated with a specified resource. Note that you can also update
     * many attributes of an existing resource using the Uploader::explicit method, which is not rate limited.
     *
     * @param string|array $publicId The public ID of the resource to update.
     * @param array        $options  The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/admin_api#update_details_of_an_existing_resource
     */
    public function update($publicId, $options = [])
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $type      = ArrayUtils::get($options, DeliveryType::KEY, DeliveryType::UPLOAD);
        $uri       = [ApiEndPoint::RESOURCES, $assetType, $type, $publicId];

        $primitive_options = ArrayUtils::whitelist(
            $options,
            [
                ModerationStatus::KEY,
                'raw_convert',
                'ocr',
                'categorization',
                'detection',
                'similarity_search',
                'auto_tagging',
                'background_removal',
                'quality_override',
                'notification_url',
            ]
        );

        $array_options = [
            'tags'               => ApiUtils::serializeSimpleApiParam(ArrayUtils::get($options, 'tags')),
            'context'            => ApiUtils::serializeContext(ArrayUtils::get($options, 'context')),
            'face_coordinates'   => ApiUtils::serializeArrayOfArrays(ArrayUtils::get($options, 'face_coordinates')),
            'custom_coordinates' => ApiUtils::serializeArrayOfArrays(ArrayUtils::get($options, 'custom_coordinates')),
            'access_control'     => ApiUtils::serializeJson(ArrayUtils::get($options, 'access_control')),
        ];

        $update_options = array_merge($primitive_options, $array_options);

        return $this->apiClient->postForm($uri, $update_options);
    }

    /**
     * Deletes resources by public IDs.
     *
     * Delete all resources with the given public IDs (up to 100).
     *
     * @param string|array $publicIds The public IDs of the resources.
     * @param array        $options   The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * @see https://cloudinary.com/documentation/admin_api#delete_resources
     */
    public function deleteResources($publicIds, $options = [])
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $type      = ArrayUtils::get($options, DeliveryType::KEY, DeliveryType::UPLOAD);
        $uri       = [ApiEndPoint::RESOURCES, $assetType, $type];

        $params = $this->prepareDeleteResourceParams($options, ['public_ids' => $publicIds]);

        return $this->apiClient->delete($uri, $params);
    }

    /**
     * Deletes resources by prefix.
     *
     * Delete all resources, including derived resources, where the public ID starts with the given prefix
     * (up to a maximum of 1000 original resources).
     *
     * @param string $prefix  The prefix of the public IDs.
     * @param array  $options The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * @see https://cloudinary.com/documentation/admin_api#delete_resources
     */
    public function deleteResourcesByPrefix($prefix, $options = [])
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $type      = ArrayUtils::get($options, DeliveryType::KEY, DeliveryType::UPLOAD);
        $uri       = [ApiEndPoint::RESOURCES, $assetType, $type];

        $params = $this->prepareDeleteResourceParams($options, ['prefix' => $prefix]);

        return $this->apiClient->delete($uri, $params);
    }

    /**
     * Deletes all resources.
     *
     * Delete all resources (of the relevant resource type and type), including derived resources
     * (up to a maximum of 1000 original resources).
     *
     * @param array $options The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * https://cloudinary.com/documentation/admin_api#delete_resources
     */
    public function deleteAllResources($options = [])
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $type      = ArrayUtils::get($options, DeliveryType::KEY, DeliveryType::UPLOAD);
        $uri       = [ApiEndPoint::RESOURCES, $assetType, $type];
        $params    = $this->prepareDeleteResourceParams($options, ['all' => true]);

        return $this->apiClient->delete($uri, $params);
    }

    /**
     * Deletes resources by tag.
     *
     * Delete all resources (and their derivatives) with the given tag name
     * (up to a maximum of 1000 original resources).
     *
     * @param string $tag     The tag name of the resources to delete
     * @param array  $options The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * @see https://cloudinary.com/documentation/admin_api#delete_resources_by_tags
     */
    public function deleteResourcesByTag($tag, $options = [])
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $uri       = [ApiEndPoint::RESOURCES, $assetType, 'tags', $tag];
        $params    = $this->prepareDeleteResourceParams($options);

        return $this->apiClient->delete($uri, $params);
    }

    /**
     * Deletes derived resources.
     *
     * Delete all derived resources with the given IDs (an array of up to 100 derived_resource_ids).
     * The derived resource IDs are returned when calling the Details of a single resource method.
     *
     * @param string|array $derived_resource_ids The derived resource IDs.
     *
     * @return ApiResponse
     *
     * @throws ApiError
     *
     * @see https://cloudinary.com/documentation/admin_api#delete_derived_resources
     */
    public function deleteDerivedResources($derived_resource_ids)
    {
        $uri    = ApiEndPoint::DERIVED_RESOURCES;
        $params = ['derived_resource_ids' => $derived_resource_ids];

        return $this->apiClient->delete($uri, $params);
    }

    /**
     * Deletes derived resources identified by transformation for the provided public_ids.
     *
     * @param string|array $publicIds       The resources the derived resources belong to.
     * @param string|array $transformations The transformation(s) associated with the derived resources.
     * @param array        $options         The optional parameters. See the admin API documentation.
     *
     * @return ApiResponse
     *
     * @throws ApiError
     */
    public function deleteDerivedByTransformation($publicIds, $transformations = [], $options = [])
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $type      = ArrayUtils::get($options, DeliveryType::KEY, DeliveryType::UPLOAD);
        $uri       = [ApiEndPoint::RESOURCES, $assetType, $type];

        $params                    = [
            'public_ids'    => ArrayUtils::build($publicIds),
            'keep_original' => true,
        ];
        $params['transformations'] = ApiUtils::serializeAssetTransformations($transformations);
        $params                    = array_merge($params, ArrayUtils::whitelist($options, ['invalidate']));

        return $this->apiClient->delete($uri, $params);
    }

    /**
     * Prepares delete resource parameters for API calls.
     *
     * @param array $options Additional options.
     * @param array $params  The parameters passed to the API.
     *
     * @return array    Updated parameters
     *
     * @internal
     */
    protected function prepareDeleteResourceParams($options, $params = [])
    {
        $filtered = ArrayUtils::whitelist($options, ['keep_original', 'next_cursor', 'invalidate']);
        if (isset($options['transformations'])) {
            $filtered['transformations'] = ApiUtils::serializeAssetTransformations($options['transformations']);
        }

        return array_merge($params, $filtered);
    }
}
