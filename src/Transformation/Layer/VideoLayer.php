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
 * Defines how to manipulate a video layer.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/video_manipulation_and_delivery#adding_video_overlays" target="_blank">
 * Video overlays</a>
 *
 * @api
 */
class VideoLayer extends AssetBasedLayer
{
    use VideoTransformationTrait;
    use VideoLayerTrait;
    use ImageLayerTrait;

    /**
     * VideoLayer constructor.
     *
     * @param $source
     */
    public function __construct($source)
    {
        parent::__construct(ClassUtils::verifyInstance($source, VideoLayerParam::class));
    }

    /**
     * Getter for the video transformation.
     *
     * Creates a new VideoTransformation if not initialized.
     *
     * @return VideoTransformation
     *
     * @internal
     */
    public function getTransformation()
    {
        if (! isset($this->transformation)) {
            $this->transformation = new VideoTransformation();
        }

        return $this->transformation;
    }

    /**
     * Getter for the layer parameter.
     *
     * @return VideoLayerParam
     *
     * @internal
     */
    protected function getLayerParam()
    {
        if (! isset($this->parameters['layer'])) {
            $this->parameters['layer'] = new VideoLayerParam(null);
        }

        return $this->parameters['layer'];
    }
}
