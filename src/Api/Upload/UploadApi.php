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

use Cloudinary\Api\ApiUtils;
use Cloudinary\Api\UploadApiClient;
use Cloudinary\ArrayUtils;
use Cloudinary\Asset\AssetType;
use Cloudinary\Configuration\CloudConfig;
use Cloudinary\Utils;
use GuzzleHttp\Promise\PromiseInterface;

/**
 * Class UploadApi for accessing Cloudinary Upload API functionality
 *
 * @see https://cloudinary.com/documentation/image_upload_api_reference
 *
 * @api
 */
class UploadApi
{
    use ArchiveTrait;
    use ContextTrait;
    use CreativeTrait;
    use EditTrait;
    use TagTrait;
    use UploadTrait;

    public const MODE_DOWNLOAD = 'download';

    /**
     * @var UploadApiClient $apiClient The HTTP API client instance.
     */
    protected $apiClient;

    /**
     * Admin Api constructor.
     *
     * @param mixed|null $configuration
     */
    public function __construct(mixed $configuration = null)
    {
        $this->apiClient = new UploadApiClient($configuration);
    }

    /**
     * Gets Upload API end point.
     *
     * @param string $action The action to perform.
     *
     * @param array  $options Additional options.
     *
     *
     * @internal
     */
    protected static function getUploadApiEndPoint(string $action, array $options = []): string
    {
        return ArrayUtils::implodeUrl([ArrayUtils::get($options, 'resource_type', AssetType::IMAGE), $action]);
    }

    /**
     * Gets upload URL for the specified asset type and endpoint.
     *
     * @param ?string $assetType The asset type.
     * @param string  $endPoint  The endpoint name.
     * @param array   $params    Additional query parameters.
     *
     */
    public function getUploadUrl(
        ?string $assetType = AssetType::AUTO,
        string $endPoint = UploadEndPoint::UPLOAD,
        array $params = []
    ): string {
        $baseUrl = $this->apiClient->getBaseUri();
        $path    = self::getUploadApiEndPoint($endPoint, ['resource_type' => $assetType]);

        return ArrayUtils::implodeFiltered('?', ["{$baseUrl}{$path}", Utils::buildHttpQuery($params)]);
    }


    /**
     * Gets cloud config of the current instance.
     *
     *
     * @internal
     */
    public function getCloud(): CloudConfig
    {
        return $this->apiClient->getCloud();
    }

    /**
     * Internal method for performing all Upload API calls.
     *
     * @param string $endPoint The relative endpoint.
     * @param array  $params   Parameters to pass to the endpoint.
     * @param array  $options  Additional options.
     *
     *
     * @internal
     */
    protected function callUploadApiAsync(
        string $endPoint = UploadEndPoint::UPLOAD,
        array $params = [],
        array $options = []
    ): PromiseInterface {
        return $this->apiClient->postAndSignFormAsync(
            self::getUploadApiEndPoint($endPoint, $options),
            ApiUtils::finalizeUploadApiParams($params)
        );
    }

    /**
     * Internal method for performing all Upload API calls as a json.
     *
     * @param string $endPoint The relative endpoint.
     * @param array  $params   Parameters to pass to the endpoint.
     * @param array  $options  Additional options.
     *
     *
     * @internal
     */
    protected function callUploadApiJsonAsync(
        string $endPoint = UploadEndPoint::UPLOAD,
        array $params = [],
        array $options = []
    ): PromiseInterface {
        return $this->apiClient->postAndSignJsonAsync(
            self::getUploadApiEndPoint($endPoint, $options),
            ApiUtils::finalizeUploadApiParams($params)
        );
    }
}
