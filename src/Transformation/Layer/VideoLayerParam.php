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
 * Class VideoLayerParam
 */
class VideoLayerParam extends BaseLayerParam
{
    /**
     * @var string $layerType The type of the layer.
     */
    protected $layerType = 'video';

    /**
     * ImageLayerParam constructor.
     *
     * @param $source
     */
    public function __construct($source)
    {
        parent::__construct();

        $this->video($source);
    }

    /**
     * Sets the video source.
     *
     * @param LayerSource|string $source The video source.
     *
     * @return $this
     */
    public function video($source)
    {
        $this->value->setValue(ClassUtils::verifyInstance($source, LayerSource::class));

        return $this;
    }
}
