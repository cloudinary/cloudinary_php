<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Api\Upload;

use Cloudinary\Api\ApiClient;
use Cloudinary\Api\ApiResponse;
use Cloudinary\Api\ApiUtils;
use Cloudinary\ArrayUtils;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * Trait EditTrait
 *
 * @property ApiClient $apiClient Defined in UploadApi class
 *
 * @api
 */
trait EditTrait
{
    /**
     * Immediately and permanently deletes a single asset from your Cloudinary cloud.
     *
     * Backed up assets are not deleted, and any assets and transformed assets already downloaded by visitors to your
     * website might still be accessible through cached copies on the CDN. You can invalidate any cached copies on the
     * CDN with the `invalidate` parameter.
     *
     * This is an asynchronous function.
     *
     * @param string $publicId The public ID of the asset to delete.
     * @param array  $options  The optional parameters.  See the upload API documentation.
     *
     * @return PromiseInterface
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#destroy_method
     */
    public function destroyAsync($publicId, $options = [])
    {
        $params              = ArrayUtils::whitelist($options, ['type', 'invalidate']);
        $params['public_id'] = $publicId;

        return $this->callUploadApiAsync(UploadEndPoint::DESTROY, $params, $options);
    }

    /**
     * Immediately and permanently deletes a single asset from your Cloudinary cloud.
     *
     * Backed up assets are not deleted, and any assets and transformed assets already downloaded by visitors to your
     * website might still be accessible through cached copies on the CDN. You can invalidate any cached copies on the
     * CDN with the `invalidate` parameter.
     *
     * @param string $publicId The public ID of the asset to delete.
     * @param array  $options  The optional parameters.  See the upload API documentation.
     *
     * @return ApiResponse
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#destroy_method
     */
    public function destroy($publicId, $options = [])
    {
        return $this->destroyAsync($publicId, $options)->wait();
    }

    /**
     * Renames the specified asset in your Cloudinary cloud.
     *
     * The existing URLs of renamed assets and their associated derived assets are no longer valid, although any
     * assets and transformed assets already downloaded by visitors to your website might still be accessible through
     * cached copies on the CDN. You can invalidate any cached copies on the CDN with the `invalidate` parameter.
     *
     * This is an asynchronous function.
     *
     * @param string $fromPublicId The public ID of the asset to rename.
     * @param string $toPublicId   The new public ID of the asset.
     * @param array  $options      The optional parameters.  See the upload API documentation.
     *
     * @return PromiseInterface
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#rename_method
     */
    public function renameAsync($fromPublicId, $toPublicId, $options = [])
    {
        $params                   = ArrayUtils::whitelist($options, [
            'type',
            'to_type',
            'invalidate',
            'overwrite',
            'context',
            'metadata'
        ]);
        $params['from_public_id'] = $fromPublicId;
        $params['to_public_id']   = $toPublicId;

        return $this->callUploadApiAsync(UploadEndPoint::RENAME, $params, $options);
    }

    /**
     * Renames the specified asset in your Cloudinary cloud.
     *
     * The existing URLs of renamed assets and their associated derived assets are no longer valid, although any
     * assets and transformed assets already downloaded by visitors to your website might still be accessible through
     * cached copies on the CDN. You can invalidate any cached copies on the CDN with the `invalidate` parameter.
     *
     * @param string $fromPublicId The public ID of the asset to rename.
     * @param string $toPublicId   The new public ID of the asset.
     * @param array  $options      The optional parameters.  See the upload API documentation.
     *
     * @return mixed
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#rename_method
     */
    public function rename($fromPublicId, $toPublicId, $options = [])
    {
        return $this->renameAsync($fromPublicId, $toPublicId, $options)->wait();
    }

    /**
     * Applies actions to an already uploaded asset.
     *
     * This is an asynchronous function.
     *
     * @param string $publicId The public ID of the asset to apply the actions to.
     * @param array  $options  The optional parameters.  See the upload API documentation.
     *
     * @return PromiseInterface
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#explicit_method
     */
    public function explicitAsync($publicId, $options = [])
    {
        $options['public_id'] = $publicId;

        $params = UploadApi::buildUploadParams($options);

        return $this->callUploadApiAsync(UploadEndPoint::EXPLICIT, $params, $options);
    }

    /**
     * Applies actions to already uploaded assets.
     *
     * @param string $publicId The public ID of the asset to apply the actions to.
     * @param array  $options  The optional parameters.  See the upload API documentation.
     *
     * @return mixed
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#explicit_method
     */
    public function explicit($publicId, $options = [])
    {
        return $this->explicitAsync($publicId, $options)->wait();
    }

    /**
     * Populates metadata fields with the given values. Existing values will be overwritten.
     *
     * Any metadata-value pairs given are merged with any existing metadata-value pairs
     * (an empty value for an existing metadata field clears the value).
     *
     * This is an asynchronous function.
     *
     * @param array $metadata   A list of custom metadata fields (by external_id) and the values to assign to each
     *                          of them.
     * @param array $publicIds  An array of Public IDs of assets uploaded to Cloudinary.
     * @param array $options    The optional parameters.  See the upload API documentation.
     *
     * @return mixed A list of public IDs that were updated.
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#metadata_method
     */
    public function updateMetadataAsync(array $metadata, array $publicIds, array $options)
    {
        $params = ArrayUtils::whitelist($options, ['type']);

        $params['metadata']   = ApiUtils::serializeContext($metadata);
        $params['public_ids'] = $publicIds; // Public IDs are not serialized similar to tags (intentionally)

        return $this->callUploadApiAsync(UploadEndPoint::METADATA, $params, $options);
    }

    /**
     * Populates metadata fields with the given values.
     *
     * Existing values will be overwritten.
     *
     * Any metadata-value pairs given are merged with any existing metadata-value pairs
     * (an empty value for an existing metadata field clears the value).
     *
     * @param array $metadata   A list of custom metadata fields (by external_id) and the values to assign to each
     *                          of them.
     * @param array $publicIds  An array of Public IDs of assets uploaded to Cloudinary.
     * @param array $options    The optional parameters.  See the upload API documentation.
     *
     * @return mixed A list of public IDs that were updated.
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#metadata_method
     */
    public function updateMetadata(array $metadata, array $publicIds, array $options = [])
    {
        return $this->updateMetadataAsync($metadata, $publicIds, $options)->wait();
    }
}
