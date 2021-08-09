<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Tag;

use Cloudinary\Api\ApiUtils;
use Cloudinary\Api\Upload\UploadApi;
use Cloudinary\Asset\AssetType;
use Cloudinary\Configuration\ApiConfig;
use Cloudinary\Configuration\Configuration;

/**
 * Class BaseConfigurableApiTag
 *
 * Represents a BaseConfigurableTag with an api configuration
 *
 * @api
 */
class BaseConfigurableApiTag extends BaseTag
{
    /**
     * @var ApiConfig $apiConfig The API configuration instance.
     */
    public $apiConfig;

    /**
     * @var array $uploadParams The upload parameters.
     */
    protected $uploadParams;

    /**
     * @var string $assetType The type of the asset.
     */
    protected $assetType;

    /**
     * @var UploadApi $uploadApi Upload API instance.
     */
    protected $uploadApi;

    /**
     * BaseConfigurableApiTag constructor.
     *
     * @param Configuration|string|array|null $configuration The Configuration source.
     * @param array                           $uploadParams  The upload parameters.
     * @param string                          $assetType     The type of the asset.
     */
    public function __construct($configuration = null, $uploadParams = [], $assetType = AssetType::AUTO)
    {
        parent::__construct($configuration);

        $this->uploadApi    = new UploadApi($configuration);
        $this->uploadParams = $uploadParams;
        $this->assetType    = $assetType;
    }

    /**
     * Returns an array with whitelisted upload params.
     *
     * If signed upload then also adds a signature param to the array.
     *
     * @return array
     * @noinspection StaticInvocationViaThisInspection
     */
    protected function getUploadParams()
    {
        $params = $this->uploadApi->buildUploadParams($this->uploadParams);

        if (! $this->config->tag->unsignedUpload) {
            ApiUtils::signRequest($params, $this->uploadApi->getCloud());
        }

        return $params;
    }

    /**
     * Sets the configuration.
     *
     * @param Configuration|string|array|null $configuration The Configuration source.
     *
     * @return Configuration
     */
    public function configuration($configuration)
    {
        $tempConfiguration = parent::configuration($configuration);
        $this->apiConfig   = $tempConfiguration->api;

        return $tempConfiguration;
    }
}
