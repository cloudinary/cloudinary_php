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

/**
 * Defines the 3D lookup table to apply to images and videos.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/image_transformations#applying_3d_luts_to_images" target="_blank">
 * Applying 3D LUTs to images</a> | <a
 * href="https://cloudinary.com/documentation/video_manipulation_and_delivery#applying_3d_luts_to_video"
 * target="_blank"> Applying 3D LUTs to videos</a>
 *
 * @api
 */
class LutLayer extends AssetBasedLayer
{
    /**
     * LutLayer constructor.
     *
     * @param $lut
     */
    public function __construct($lut)
    {
        parent::__construct(ClassUtils::verifyInstance($lut, LutLayerParam::class));
    }

    /**
     * Gets the transformation.
     *
     * @return Transformation
     */
    public function getTransformation()
    {
        if (! isset($this->transformation)) {
            $this->transformation = new Transformation();
        }

        return $this->transformation;
    }

    /**
     * Gets the layer parameter.
     *
     * @return LutLayerParam
     *
     * @internal
     */
    protected function getLayerParam()
    {
        if (! isset($this->parameters['layer'])) {
            $this->parameters['layer'] = new LutLayerParam(null);
        }

        return $this->parameters['layer'];
    }
}
