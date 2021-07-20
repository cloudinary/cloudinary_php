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

    const MODE_DOWNLOAD = 'download';

    /**
     * @var UploadApiClient $apiClient The HTTP API client instance.
     */
    protected $apiClient;

    /**
     * Admin Api constructor.
     *
     * @param mixed $configuration
     */
    public function __construct($configuration = null)
    {
        $this->apiClient = new UploadApiClient($configuration);
    }

    /**
     * Gets Upload API end point.
     *
     * @param string $action  The action to perform.
     *
     * @param array  $options Additional options.
     *
     * @return string
     *
     * @internal
     */
    protected static function getUploadApiEndPoint($action, $options = [])
    {
        return ArrayUtils::implodeUrl([ArrayUtils::get($options, 'resource_type', AssetType::IMAGE), $action]);
    }

    /**
     * Gets upload URL for the specified asset type and endpoint.
     *
     * @param string $assetType The asset type.
     * @param string $endPoint  The endpoint name.
     * @param array  $params    Additional query parameters.
     *
     * @return string
     */
    public function getUploadUrl($assetType = AssetType::AUTO, $endPoint = UploadEndPoint::UPLOAD, $params = [])
    {
        $baseUrl = $this->apiClient->getBaseUri();
        $path    = self::getUploadApiEndPoint($endPoint, ['resource_type' => $assetType]);

        return ArrayUtils::implodeFiltered('?', ["{$baseUrl}{$path}", Utils::buildHttpQuery($params)]);
    }


    /**
     * Gets cloud config of the current instance.
     *
     * @return CloudConfig
     *
     * @internal
     */
    public function getCloud()
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
     * @return PromiseInterface
     *
     * @internal
     */
    protected function callUploadApiAsync($endPoint = UploadEndPoint::UPLOAD, $params = [], $options = [])
    {
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
     * @return PromiseInterface
     *
     * @internal
     */
    protected function callUploadApiJsonAsync($endPoint = UploadEndPoint::UPLOAD, $params = [], $options = [])
    {
        return $this->apiClient->postAndSignJsonAsync(
            self::getUploadApiEndPoint($endPoint, $options),
            ApiUtils::finalizeUploadApiParams($params)
        );
    }
}
