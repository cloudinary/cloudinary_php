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

/**
 * Defines how to manipulate an image layer.
 *
 * **Learn more**: <a
 * href=https://cloudinary.com/documentation/image_transformations#image_and_text_overlays target="_blank">
 * Image overlays</a>
 *
 * @api
 */
class ImageLayer extends AssetBasedLayer implements ImageTransformationInterface
{
    use ImageTransformationTrait;
    use ImageLayerTrait;

    /**
     * ImageLayer constructor.
     *
     * @param $source
     */
    public function __construct($source)
    {
        parent::__construct($source);
    }

    /**
     * Gets the transformation.
     *
     * @return ImageTransformation
     *
     * @internal
     */
    public function getTransformation()
    {
        if (! isset($this->transformation)) {
            $this->transformation = new ImageTransformation();
        }

        return $this->transformation;
    }

    /**
     * Tiles the added overlay over the entire image.
     *
     * @return ImageLayer
     */
    public function tiled()
    {
        return $this->addFlag(Flag::tiled());
    }

    /**
     * Gets the layer parameter.
     *
     * @return ImageLayerParam
     *
     * @internal
     */
    protected function getLayerParam()
    {
        if (! isset($this->parameters['layer'])) {
            $this->parameters['layer'] = new ImageLayerParam(null);
        }

        return $this->parameters['layer'];
    }
}
