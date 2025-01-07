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
 * Enables you to manage the assets in your cloud.
 *
 * **Learn more**: <a
 * href=https://cloudinary.com/documentation/admin_api#resources target="_blank">
 * Resources method - Admin API</a>
 *
 * @property ApiClient $apiClient Defined in AdminApi class.
 *
 * @api
 */
trait AssetsTrait
{
    /**
     * Lists available asset types.
     *
     */
    public function assetTypes(): ApiResponse
    {
        return $this->apiClient->get(ApiEndPoint::ASSETS);
    }

    /**
     * Lists all uploaded assets filtered by any specified options.
     *
     * @param array $options The optional parameters. See the
     * <a href=https://cloudinary.com/documentation/admin_api#get_resources target="_blank"> Admin API</a> documentation.
     *
     *
     * @see https://cloudinary.com/documentation/admin_api#get_resources
     */
    public function assets(array $options = []): ApiResponse
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $uri       = [ApiEndPoint::ASSETS, $assetType];
        ArrayUtils::appendNonEmpty($uri, ArrayUtils::get($options, DeliveryType::KEY));

        $params = array_merge(
            self::prepareListAssetsParams($options),
            ArrayUtils::whitelist($options, ['prefix', 'direction'])
        );

        return $this->apiClient->get($uri, $params);
    }

    /**
     * Lists assets with the specified tag.
     *
     * This method does not return matching deleted assets, even if they have been backed up.
     *
     * @param string $tag     The tag value.
     * @param array  $options The optional parameters. See the
     * <a href=https://cloudinary.com/documentation/admin_api#get_resources_by_tag target="_blank"> Admin API</a> documentation.
     *
     *
     * @see https://cloudinary.com/documentation/admin_api#get_resources_by_tag
     */
    public function assetsByTag(string $tag, array $options = []): ApiResponse
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $uri       = [ApiEndPoint::ASSETS, $assetType, 'tags', $tag];
        $params    = self::prepareListAssetsParams($options);

        return $this->apiClient->get($uri, $params);
    }

    /**
     * Lists assets with the specified contextual metadata.
     *
     * This method does not return matching deleted assets, even if they have been backed up.
     *
     * @param string      $key     Only assets with this context key are returned.
     * @param string|null $value   Only assets with this context value for the specified context key are returned.
     *                        If this parameter is not provided, all assets with the specified context key are returned,
     *                        regardless of the key value.
     * @param array       $options The optional parameters. See the
     * <a href=https://cloudinary.com/documentation/admin_api#get_resources_by_context target="_blank"> Admin API</a> documentation.
     *
     *
     * @see https://cloudinary.com/documentation/admin_api#get_resources_by_context
     */
    public function assetsByContext(string $key, ?string $value = null, array $options = []): ApiResponse
    {
        $assetType       = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $uri             = [ApiEndPoint::ASSETS, $assetType, 'context'];
        $params          = self::prepareListAssetsParams($options);
        $params['key']   = $key;
        $params['value'] = $value;

        return $this->apiClient->get($uri, $params);
    }

    /**
     * Lists assets currently in the specified moderation queue and status.
     *
     * @param string $kind    Type of image moderation queue to list.
     *                        Valid values:  "manual", "webpurify", "aws_rek", or "metascan".
     * @param string $status  Only assets with this moderation status will be returned.
     *                        Valid values: "pending", "approved", "rejected".
     * @param array  $options The optional parameters. See the
     * <a href=https://cloudinary.com/documentation/admin_api#get_resources_in_moderation_queues target="_blank"> Admin API</a> documentation.
     *
     *
     * @see https://cloudinary.com/documentation/admin_api#get_resources_in_moderation_queues
     */
    public function assetsByModeration(string $kind, string $status, array $options = []): ApiResponse
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $uri       = [ApiEndPoint::ASSETS, $assetType, 'moderations', $kind, $status];

        $params = self::prepareListAssetsParams($options);

        return $this->apiClient->get($uri, $params);
    }

    /**
     * Lists assets with the specified public IDs.
     *
     * @param array|string $publicIds The requested public_ids (up to 100).
     * @param array        $options   The optional parameters. See the
     * <a href=https://cloudinary.com/documentation/admin_api#get_resources target="_blank"> Admin API</a> documentation.
     *
     *
     * @see https://cloudinary.com/documentation/admin_api#get_resources
     */
    public function assetsByIds(array|string $publicIds, array $options = []): ApiResponse
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $type      = ArrayUtils::get($options, DeliveryType::KEY, DeliveryType::UPLOAD);
        $uri       = [ApiEndPoint::ASSETS, $assetType, $type];

        $params               = self::prepareAssetsParams($options);
        $params['public_ids'] = $publicIds;

        return $this->apiClient->get($uri, $params);
    }

    /**
     * Lists assets with the specified asset IDs.
     *
     * @param array|string $assetIds The requested asset IDs.
     * @param array        $options  The optional parameters. See the
     * <a href=https://cloudinary.com/documentation/admin_api#get_resources target="_blank"> Admin API</a> documentation.
     *
     *
     * @see https://cloudinary.com/documentation/admin_api#get_resources
     */
    public function assetsByAssetIds(array|string $assetIds, array $options = []): ApiResponse
    {
        $uri = [ApiEndPoint::ASSETS, 'by_asset_ids'];

        $params              = self::prepareAssetsParams($options);
        $params['asset_ids'] = $assetIds;

        return $this->apiClient->get($uri, $params);
    }

    /**
     * Lists assets in the specified asset folder.
     *
     * @param string $assetFolder The asset folder.
     * @param array  $options     The optional parameters. See the
     *                            <a href=https://cloudinary.com/documentation/dynamic_folders target="_blank"> Admin
     *                            API</a> documentation.
     *
     *
     * @see https://cloudinary.com/documentation/dynamic_folders
     */
    public function assetsByAssetFolder(string $assetFolder, array $options = []): ApiResponse
    {
        $uri = [ApiEndPoint::ASSETS, 'by_asset_folder'];

        $params                 =  self::prepareListAssetsParams($options);
        $params['asset_folder'] = $assetFolder;

        return $this->apiClient->get($uri, $params);
    }


    /**
     * Find images based on their visual content.
     *
     * @param array $options The optional parameters. See the
     *                       <a href=https://cloudinary.com/documentation/admin_api#visual_search_for_resources
     *                       target="_blank"> AdminAPI</a> documentation.
     *
     *
     * @throws ApiError
     *
     * @see https://cloudinary.com/documentation/admin_api#visual_search_for_resources
     */
    public function visualSearch(array $options = []): ApiResponse
    {
        $uri = [ApiEndPoint::ASSETS, 'visual_search'];

        $params = ArrayUtils::whitelist($options, ['image_url', 'image_asset_id', 'text']);

        // Special handling for file inside Admin API.
        if (array_key_exists('image_file', $options)) {
            $options['file_field'] = 'image_file';
            $options['unsigned'] = true;

            return $this->apiClient->postFile($uri, $options['image_file'], $params, $options);
        }

        return $this->apiClient->postForm($uri, $params);
    }

    /**
     * Returns the details of the specified asset and all its derived assets.
     *
     *
     * Note that if you only need details about the original asset,
     * you can also use the Uploader::upload or Uploader::explicit methods, which return the same information and
     * are not rate limited.
     *
     * @param string $publicId The public ID of the asset.
     * @param array  $options  The optional parameters. See the
     * <a href=https://cloudinary.com/documentation/admin_api#get_the_details_of_a_single_resource target="_blank"> Admin API</a> documentation.
     *
     *
     * @see https://cloudinary.com/documentation/admin_api#get_the_details_of_a_single_resource
     */
    public function asset(string $publicId, array $options = []): ApiResponse
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $type      = ArrayUtils::get($options, DeliveryType::KEY, DeliveryType::UPLOAD);
        $uri       = [ApiEndPoint::ASSETS, $assetType, $type, $publicId];

        $params = self::prepareAssetDetailsParams($options);

        return $this->apiClient->get($uri, $params);
    }

    /**
     * Returns the details of the specified asset and all its derived assets by asset id.
     *
     *
     * Note that if you only need details about the original asset,
     * you can also use the Uploader::upload or Uploader::explicit methods, which return the same information and
     * are not rate limited.
     *
     * @param string $assetId The Asset ID of the asset.
     * @param array  $options The optional parameters. See the
     *                        <a
     *                        href=https://cloudinary.com/documentation/admin_api#get_the_details_of_a_single_resource
     *                        target="_blank"> Admin API</a> documentation.
     *
     *
     * @see https://cloudinary.com/documentation/admin_api#get_the_details_of_a_single_resource
     */
    public function assetByAssetId(string $assetId, array $options = []): ApiResponse
    {
        $uri = [ApiEndPoint::ASSETS, $assetId];

        $params = self::prepareAssetDetailsParams($options);

        return $this->apiClient->get($uri, $params);
    }

    /**
     * Reverts to the latest backed up version of the specified deleted assets.
     *
     * @param array|string $publicIds The public IDs of the backed up assets to restore. They can be existing or
     * deleted assets.
     * @param array        $options   The optional parameters. See the
     * <a href=https://cloudinary.com/documentation/admin_api#restore_resources target="f_blank"> Admin API</a> documentation.
     *
     *
     * @see https://cloudinary.com/documentation/admin_api#restore_resources
     */
    public function restore(array|string $publicIds, array $options = []): ApiResponse
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $type      = ArrayUtils::get($options, DeliveryType::KEY, DeliveryType::UPLOAD);
        $uri       = [ApiEndPoint::ASSETS, $assetType, $type, 'restore'];

        $params = array_merge($options, ['public_ids' => $publicIds]);

        return $this->apiClient->postJson($uri, $params);
    }

    /**
     * Reverts to the latest backed up version of the specified deleted assets by asset IDs.
     *
     * @param array|string $assetIds The asset IDs of the backed up assets to restore. They can be existing or
     *                               deleted assets.
     * @param array        $options  The optional parameters.
     *
     * @return ApiResponse The result of the restore operation.
     */
    public function restoreByAssetIds(array|string $assetIds, array $options = []): ApiResponse
    {
        $uri = [ApiEndPoint::ASSETS, 'restore'];

        $params = array_merge($options, ['asset_ids' => ArrayUtils::build($assetIds)]);

        return $this->apiClient->postJson($uri, $params);
    }

    /**
     * Updates details of an existing asset.
     *
     * Update one or more of the attributes associated with a specified asset. Note that you can also update
     * most attributes of an existing asset using the Uploader::explicit method, which is not rate limited.
     *
     * @param array|string $publicId The public ID of the asset to update.
     * @param array        $options  The optional parameters. See the
     * <a href=https://cloudinary.com/documentation/admin_api#update_details_of_an_existing_resource target="_blank"> Admin API</a> documentation.
     *
     *
     * @see https://cloudinary.com/documentation/admin_api#update_details_of_an_existing_resource
     */
    public function update(array|string $publicId, array $options = []): ApiResponse
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $type      = ArrayUtils::get($options, DeliveryType::KEY, DeliveryType::UPLOAD);
        $uri       = [ApiEndPoint::ASSETS, $assetType, $type, $publicId];

        $primitiveOptions = ArrayUtils::whitelist(
            $options,
            [
                ModerationStatus::KEY,
                'raw_convert',
                'ocr',
                'categorization',
                'detection',
                'similarity_search',
                'visual_search',
                'auto_tagging',
                'background_removal',
                'quality_override',
                'notification_url',
                'asset_folder',
                'unique_display_name',
            ]
        );

        $arrayOptions = [
            'tags'               => ApiUtils::serializeSimpleApiParam(ArrayUtils::get($options, 'tags')),
            'context'            => ApiUtils::serializeContext(ArrayUtils::get($options, 'context')),
            'metadata'           => ApiUtils::serializeContext(ArrayUtils::get($options, 'metadata')),
            'face_coordinates'   => ApiUtils::serializeArrayOfArrays(ArrayUtils::get($options, 'face_coordinates')),
            'custom_coordinates' => ApiUtils::serializeArrayOfArrays(ArrayUtils::get($options, 'custom_coordinates')),
            'access_control'     => ApiUtils::serializeJson(ArrayUtils::get($options, 'access_control')),
        ];

        $updateOptions = array_merge($primitiveOptions, $arrayOptions);

        return $this->apiClient->postForm($uri, $updateOptions);
    }

    /**
     * Deletes the specified assets.
     *
     * @param array|string $publicIds The public IDs of the assets to delete (up to 100).
     * @param array        $options   The optional parameters. See the
     * <a href=https://cloudinary.com/documentation/admin_api#sdelete_resources target="_blank"> Admin API</a> documentation.
     *
     * @see https://cloudinary.com/documentation/admin_api#delete_resources
     */
    public function deleteAssets(array|string $publicIds, array $options = []): ApiResponse
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $type      = ArrayUtils::get($options, DeliveryType::KEY, DeliveryType::UPLOAD);
        $uri       = [ApiEndPoint::ASSETS, $assetType, $type];

        $params = self::prepareDeleteAssetParams($options, ['public_ids' => $publicIds]);

        return $this->apiClient->delete($uri, $params);
    }

    /**
     * Deletes the specified assets by asset IDs.
     *
     * @param array|string $assetIds The asset IDs of the assets to delete.
     * @param array        $options  Additional optional parameters.
     *
     * @return ApiResponse The result of the command.
     */
    public function deleteAssetsByAssetIds(array|string $assetIds, array $options = []): ApiResponse
    {
        $uri = [ApiEndPoint::ASSETS];
        $params = self::prepareDeleteAssetParams($options, ['asset_ids' => ArrayUtils::build($assetIds)]);

        return $this->apiClient->deleteJson($uri, $params);
    }

    /**
     * Deletes assets by prefix.
     *
     * Delete up to 1000 original assets, along with their derived assets, where the public ID starts with the
     * specified prefix.
     *
     * @param string $prefix  The Public ID prefix.
     * @param array  $options The optional parameters. See the
     * <a href=https://cloudinary.com/documentation/admin_api#delete_resources target="_blank"> Admin API</a> documentation.
     *
     *
     *
     * @see https://cloudinary.com/documentation/admin_api#delete_resources
     */
    public function deleteAssetsByPrefix(string $prefix, array $options = []): ApiResponse
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $type      = ArrayUtils::get($options, DeliveryType::KEY, DeliveryType::UPLOAD);
        $uri       = [ApiEndPoint::ASSETS, $assetType, $type];

        $params = self::prepareDeleteAssetParams($options, ['prefix' => $prefix]);

        return $this->apiClient->delete($uri, $params);
    }

    /**
     * Deletes all assets of the specified asset and delivery type, including their derived assets.
     *
     * Supports deleting up to a maximum of 1000 original assets in a single call.
     *
     * @param array $options The optional parameters. See the
     * <a href=https://cloudinary.com/documentation/admin_api#delete_resources target="_blank"> Admin API</a> documentation.
     *
     *
     *
     * https://cloudinary.com/documentation/admin_api#delete_resources
     */
    public function deleteAllAssets(array $options = []): ApiResponse
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $type      = ArrayUtils::get($options, DeliveryType::KEY, DeliveryType::UPLOAD);
        $uri       = [ApiEndPoint::ASSETS, $assetType, $type];
        $params    = self::prepareDeleteAssetParams($options, ['all' => true]);

        return $this->apiClient->delete($uri, $params);
    }

    /**
     * Deletes assets with the specified tag, including their derived assets.
     *
     * Supports deleting up to a maximum of 1000 original assets in a single call.
     *
     * @param string $tag     The tag value of the assets to delete.
     * @param array  $options The optional parameters. See the
     * <a href=https://cloudinary.com/documentation/admin_api#delete_resources_by_tags target="_blank"> Admin API</a> documentation.
     *
     *
     *
     * @see https://cloudinary.com/documentation/admin_api#delete_resources_by_tags
     */
    public function deleteAssetsByTag(string $tag, array $options = []): ApiResponse
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $uri       = [ApiEndPoint::ASSETS, $assetType, 'tags', $tag];
        $params    = self::prepareDeleteAssetParams($options);

        return $this->apiClient->delete($uri, $params);
    }

    /**
     * Deletes the specified derived assets by derived asset ID.
     *
     * The derived asset IDs for a particular original asset are returned when calling the `asset` method to
     * return the details of a single asset.
     *
     * @param array|string $derived_asset_ids The derived asset IDs (up to 100 ids).
     *
     *
     *
     * @see https://cloudinary.com/documentation/admin_api##delete_resources
     */
    public function deleteDerivedAssets(array|string $derived_asset_ids): ApiResponse
    {
        $uri    = ApiEndPoint::DERIVED_ASSETS;
        $params = ['derived_resource_ids' => $derived_asset_ids];

        return $this->apiClient->delete($uri, $params);
    }

    /**
     * Deletes derived assets identified by transformation and public_ids.
     *
     * @param array|string $publicIds       The public IDs for which you want to delete derived assets.
     * @param array|string $transformations The transformation(s) associated with the derived assets to delete.
     * @param array        $options         The optional parameters. See the
     * <a href=https://cloudinary.com/documentation/admin_api#resources target="_blank"> Admin API</a> documentation.
     *
     *
     */
    public function deleteDerivedByTransformation(array|string $publicIds, array|string $transformations = [], array $options = []): ApiResponse
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $type      = ArrayUtils::get($options, DeliveryType::KEY, DeliveryType::UPLOAD);
        $uri       = [ApiEndPoint::ASSETS, $assetType, $type];

        $params                    = [
            'public_ids'    => ArrayUtils::build($publicIds),
            'keep_original' => true,
        ];
        $params['transformations'] = ApiUtils::serializeAssetTransformations($transformations);
        $params                    = array_merge($params, ArrayUtils::whitelist($options, ['invalidate']));

        return $this->apiClient->delete($uri, $params);
    }

    /**
     * Relates an asset to other assets by public IDs.
     *
     * @param string $publicId       The public ID of the asset to update.
     * @param array  $assetsToRelate The array of up to 10 fully_qualified_public_ids given as
     *                              resource_type/type/public_id.
     * @param array  $options        The optional parameters. See the
     * <a href=https://cloudinary.com/documentation/admin_api#add_related_assets target="_blank"> Admin API</a> documentation.
     *
     */
    public function addRelatedAssets(string $publicId, array $assetsToRelate, array $options = []): ApiResponse
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $type      = ArrayUtils::get($options, DeliveryType::KEY, DeliveryType::UPLOAD);

        $uri       = [ApiEndPoint::ASSETS, ApiEndPoint::RELATED_ASSETS, $assetType, $type, $publicId];

        $params = [
            'assets_to_relate' => ArrayUtils::build($assetsToRelate),
        ];

        return $this->apiClient->postJson($uri, $params);
    }

    /**
     * Relates an asset to other assets by asset IDs.
     *
     * @param string $assetId        The asset ID of the asset to update.
     * @param array  $assetsToRelate The array of up to 10 asset IDs.
     *
     */
    public function addRelatedAssetsByAssetIds(string $assetId, array $assetsToRelate): ApiResponse
    {
        $uri = [ApiEndPoint::ASSETS, ApiEndPoint::RELATED_ASSETS, $assetId];

        $params = [
            'assets_to_relate' => ArrayUtils::build($assetsToRelate),
        ];

        return $this->apiClient->postJson($uri, $params);
    }

    /**
     * Unrelates an asset from other assets by public IDs.
     *
     * @param string $publicId         The public ID of the asset to update.
     * @param array  $assetsToUnrelate The array of up to 10 fully_qualified_public_ids given as
     *                                resource_type/type/public_id.
     * @param array  $options          The optional parameters. See the
     * <a href=https://cloudinary.com/documentation/admin_api#delete_related_assets target="_blank"> Admin API</a> documentation.
     *
     */
    public function deleteRelatedAssets(string $publicId, array $assetsToUnrelate, array $options = []): ApiResponse
    {
        $assetType = ArrayUtils::get($options, AssetType::KEY, AssetType::IMAGE);
        $type      = ArrayUtils::get($options, DeliveryType::KEY, DeliveryType::UPLOAD);

        $uri       = [ApiEndPoint::ASSETS, ApiEndPoint::RELATED_ASSETS, $assetType, $type, $publicId];

        $params = [
            'assets_to_unrelate' => ArrayUtils::build($assetsToUnrelate),
        ];

        return $this->apiClient->deleteJson($uri, $params);
    }

    /**
     * Unrelates an asset from other assets by asset IDs.
     *
     * @param string $assetId          The asset ID of the asset to update.
     * @param array  $assetsToUnrelate The array of up to 10 asset IDs.
     *
     */
    public function deleteRelatedAssetsByAssetIds(string $assetId, array $assetsToUnrelate): ApiResponse
    {
        $uri       = [ApiEndPoint::ASSETS, ApiEndPoint::RELATED_ASSETS, $assetId];

        $params = [
            'assets_to_unrelate' => ArrayUtils::build($assetsToUnrelate),
        ];

        return $this->apiClient->deleteJson($uri, $params);
    }

    /**
     * Prepares optional parameters for delete asset API calls.
     *
     * @param array $options Additional options.
     * @param array $params  The parameters passed to the API.
     *
     * @return array    Updated parameters
     *
     * @internal
     */
    protected static function prepareDeleteAssetParams(array $options, array $params = []): array
    {
        $filtered = ArrayUtils::whitelist($options, ['keep_original', 'next_cursor', 'invalidate']);
        if (isset($options['transformations'])) {
            $filtered['transformations'] = ApiUtils::serializeAssetTransformations($options['transformations']);
        }

        return array_merge($params, $filtered);
    }

    /**
     * Prepares optional parameters for asset/assetByAssetId API calls.
     *
     * @param array $options Additional options.
     *
     * @return array    Optional parameters
     *
     * @internal
     */
    protected static function prepareAssetDetailsParams(array $options): array
    {
        return ArrayUtils::whitelist(
            $options,
            [
                'exif',
                'colors',
                'faces',
                'quality_analysis',
                'image_metadata',
                'media_metadata',
                'phash',
                'pages',
                'cinemagraph_analysis',
                'coordinates',
                'max_results',
                'derived_next_cursor',
                'accessibility_analysis',
                'versions',
                'related',
                'related_next_cursor',
            ]
        );
    }

    /**
     * Prepares optional parameters for assets* API calls.
     *
     * @param array $options Additional options.
     *
     * @return array    Optional parameters
     *
     * @internal
     */
    protected static function prepareAssetsParams(array $options): array
    {
        $params = ArrayUtils::whitelist($options, ['tags', 'context', 'metadata', 'moderations']);
        $params['fields'] = ApiUtils::serializeSimpleApiParam(ArrayUtils::get($options, 'fields'));

        return $params;
    }

    /**
     * Prepares optional parameters for assetsBy* API calls.
     *
     * @param array $options Additional options.
     *
     * @return array    Optional parameters
     *
     * @internal
     */
    protected static function prepareListAssetsParams(array $options): array
    {
        return array_merge(
            self::prepareAssetsParams($options),
            ArrayUtils::whitelist($options, ['next_cursor', 'max_results', 'direction'])
        );
    }
}
