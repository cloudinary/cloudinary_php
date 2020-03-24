<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation;

use Cloudinary\ClassUtils;
use Cloudinary\StringUtils;

/**
 * Class FetchLayerParam
 */
class FetchLayerParam extends BaseLayerParam
{
    /**
     * @var string|LayerRemoteSource $layerType The type of the layer.
     */
    protected $layerType = 'fetch';

    /**
     * FetchLayerParam constructor.
     *
     * @param string $fetchUrl The URL of the asset.
     */
    public function __construct($fetchUrl)
    {
        parent::__construct();

        $this->fetchUrl($fetchUrl);
    }

    /**
     * Sets the URL of the remote asset.
     *
     * @param string|LayerRemoteSource $fetchUrl The URL of the asset.
     *
     * @return $this
     */
    public function fetchUrl($fetchUrl)
    {
        $this->setParamValue(
            ClassUtils::verifyInstance(StringUtils::truncatePrefix($fetchUrl, 'fetch:'), LayerRemoteSource::class)
        );

        return $this;
    }
}
