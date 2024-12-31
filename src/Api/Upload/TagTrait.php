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
use Cloudinary\ArrayUtils;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * Trait TagTrait
 *
 * @property ApiClient $apiClient Defined in UploadApi class.
 *
 * @api
 */
trait TagTrait
{
    /**
     * Adds a tag to the assets specified.
     *
     * This is an asynchronous function.
     *
     * @param string $tag       The name of the tag to add.
     * @param array  $publicIds The public IDs of the assets to add the tag to.
     * @param array  $options   The optional parameters. See the upload API documentation.
     *
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#tags_method
     */
    public function addTagAsync(string $tag, array $publicIds = [], array $options = []): PromiseInterface
    {
        return $this->callTagsApiAsync(TagCommand::ADD, $tag, $publicIds, $options);
    }

    /**
     * Adds a tag to the assets specified.
     *
     * @param string $tag       The name of the tag to add.
     * @param array  $publicIds The public IDs of the assets to add the tag to.
     * @param array  $options   The optional parameters. See the upload API documentation.
     *
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#tags_method
     */
    public function addTag(string $tag, array $publicIds = [], array $options = []): ApiResponse
    {
        return $this->addTagAsync($tag, $publicIds, $options)->wait();
    }

    /**
     * Removes a tag from the assets specified.
     *
     * This is an asynchronous function.
     *
     * @param string       $tag       The name of the tag to remove.
     * @param array|string $publicIds The public IDs of the assets to remove the tags from.
     * @param array        $options   The optional parameters. See the upload API documentation.
     *
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#tags_method
     */
    public function removeTagAsync(string $tag, array|string $publicIds = [], array $options = []): PromiseInterface
    {
        return $this->callTagsApiAsync(TagCommand::REMOVE, $tag, $publicIds, $options);
    }

    /**
     * Removes a tag from the assets specified.
     *
     * @param string       $tag       The name of the tag to remove.
     * @param array|string $publicIds The public IDs of the assets to remove the tags from.
     * @param array        $options   The optional parameters. See the upload API documentation.
     *
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#tags_method
     */
    public function removeTag(string $tag, array|string $publicIds = [], array $options = []): ApiResponse
    {
        return $this->removeTagAsync($tag, $publicIds, $options)->wait();
    }

    /**
     * Removes all tags from the assets specified.
     *
     * This is an asynchronous function.
     *
     * @param array $publicIds The public IDs of the assets to remove all tags from.
     * @param array $options   The optional parameters. See the upload API documentation.
     *
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#tags_method
     */
    public function removeAllTagsAsync(array $publicIds = [], array $options = []): PromiseInterface
    {
        return $this->callTagsApiAsync(TagCommand::REMOVE_ALL, null, $publicIds, $options);
    }

    /**
     * Removes all tags from the assets specified.
     *
     * @param array $publicIds The public IDs of the assets to remove all tags from.
     * @param array $options   The optional parameters. See the upload API documentation.
     *
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#tags_method
     */
    public function removeAllTags(array $publicIds = [], array $options = []): ApiResponse
    {
        return $this->removeAllTagsAsync($publicIds, $options)->wait();
    }

    /**
     * Replaces all existing tags on the assets specified with the tag specified.
     *
     * This is an asynchronous function.
     *
     * @param string       $tag       The new tag with which to replace the existing tags.
     * @param array|string $publicIds The public IDs of the assets to replace the tags of.
     * @param array        $options   The optional parameters. See the upload API documentation.
     *
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#tags_method
     */
    public function replaceTagAsync(string $tag, array|string $publicIds = [], array $options = []): PromiseInterface
    {
        return $this->callTagsApiAsync(TagCommand::REPLACE, $tag, $publicIds, $options);
    }

    /**
     * Replaces all existing tags on the assets specified with the tag specified.
     *
     * @param string       $tag       The new tag with which to replace the existing tags.
     * @param array|string $publicIds The public IDs of the assets to replace the tags of.
     * @param array        $options   The optional parameters. See the upload API documentation.
     *
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#tags_method
     */
    public function replaceTag(string $tag, array|string $publicIds = [], array $options = []): ApiResponse
    {
        return $this->replaceTagAsync($tag, $publicIds, $options)->wait();
    }

    /**
     * Internal call to the tags API.
     *
     * @param string       $command   The command to perform. See TagCommand class for available commands.
     * @param string|null  $tag       The tag.
     * @param array|string $publicIds The public IDs of the assets to apply tag to.
     * @param array        $options   The optional parameters.
     *
     * @see TagCommand
     *
     * @internal
     */
    protected function callTagsApiAsync(
        string $command,
        ?string $tag,
        array|string $publicIds = [],
        array $options = []
    ): PromiseInterface {
        $params = [
            'tag'        => $tag,
            'public_ids' => $publicIds,
            'type'       => ArrayUtils::get($options, 'type'),
            'command'    => $command,
        ];

        return $this->callUploadApiAsync(UploadEndPoint::TAGS, $params, $options);
    }
}
