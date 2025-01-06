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
 * Trait ContextTrait
 *
 * @property ApiClient $apiClient Defined in UploadApi class.
 *
 * @api
 */
trait ContextTrait
{
    /**
     * Adds context metadata as key-value pairs to the the specified assets.
     *
     * This is an asynchronous function.
     *
     * @param array|string $context   The key-value pairs of context metadata.
     * @param array|string $publicIds The public IDs of the assets to add context metadata to.
     * @param array        $options   The optional parameters. See the upload API documentation.
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#context_method
     */
    public function addContextAsync(
        array|string $context,
        array|string $publicIds = [],
        array $options = []
    ): PromiseInterface {
        return $this->callContextApiAsync(ContextCommand::ADD, $context, $publicIds, $options);
    }

    /**
     * Adds context metadata as key-value pairs to the the specified assets.
     *
     * @param array|string $context   The key-value pairs of context metadata.
     * @param array|string $publicIds The public IDs of the assets to add context metadata to.
     * @param array        $options   The optional parameters. See the upload API documentation.
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#context_method
     */
    public function addContext(array|string $context, array|string $publicIds = [], array $options = []): ApiResponse
    {
        return $this->addContextAsync($context, $publicIds, $options)->wait();
    }

    /**
     * Removes all context metadata from the specified assets.
     *
     * This is an asynchronous function.
     *
     * @param array|string $publicIds The public IDs of the assets to remove context metadata from.
     * @param array        $options   The optional parameters. See the upload API documentation.
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#context_method
     */
    public function removeAllContextAsync(array|string $publicIds = [], array $options = []): PromiseInterface
    {
        return $this->callContextApiAsync(ContextCommand::REMOVE_ALL, null, $publicIds, $options);
    }

    /**
     * Removes all context metadata from the specified assets.
     *
     * @param array|string $publicIds The public IDs of the assets to remove context metadata from.
     * @param array        $options   The optional parameters. See the upload API documentation.
     *
     * @see https://cloudinary.com/documentation/image_upload_api_reference#context_method
     */
    public function removeAllContext(array|string $publicIds = [], array $options = []): ApiResponse
    {
        return $this->removeAllContextAsync($publicIds, $options)->wait();
    }

    /**
     * Internal call to the context API.
     *
     * @param string            $command   The command to perform. See ContextCommand class for available commands.
     * @param array|string|null $context   The key-value pairs of context metadata.
     * @param array|string      $publicIds The public IDs of the assets to apply context to.
     * @param array             $options   The optional parameters.
     *
     * @see ContextCommand
     *
     * @internal
     */
    protected function callContextApiAsync(
        string $command,
        array|string|null $context,
        array|string $publicIds = [],
        array $options = []
    ): PromiseInterface {
        $params = [
            'context'    => ApiUtils::serializeContext($context),
            'public_ids' => ArrayUtils::build($publicIds),
            'type'       => ArrayUtils::get($options, 'type'),
            'command'    => $command,
        ];

        return $this->callUploadApiAsync(UploadEndPoint::CONTEXT, $params, $options);
    }
}
