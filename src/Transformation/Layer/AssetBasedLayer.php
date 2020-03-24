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

use Cloudinary\Asset\AssetDescriptor;
use Cloudinary\Asset\BaseAsset;

/**
 * Class AssetBasedLayer
 */
abstract class AssetBasedLayer extends BaseLayer
{
    /**
     * AssetBasedLayer constructor.
     *
     * @param $asset
     */
    public function __construct($asset = null)
    {
        parent::__construct();

        $this->setSource($asset);
    }

    /**
     * Sets the source of the layer.
     *
     * @param string|BaseLayerParam $source The source.
     *
     * @return $this
     */
    public function setSource($source)
    {
        if ($source instanceof BaseLayerParam) {
            $this->getLayerParam()->setParamValue($source->getValue());

            return $this;
        }

        $assetDescriptor = null;

        if ($source instanceof BaseAsset) {
            $assetDescriptor = $source->asset;
            if ($source->transformation) {
                $this->transformation = clone $source->transformation;
            }
        } else { // string
            $assetDescriptor = new AssetDescriptor($source);
        }

        $this->getLayerParam()->setParamValue(new LayerSource($assetDescriptor->publicId()));

        return $this;
    }
}
