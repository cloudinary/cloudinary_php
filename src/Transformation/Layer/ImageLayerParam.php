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
 * Class ImageLayerParam
 */
class ImageLayerParam extends BaseLayerParam
{
    use LayerSourceTrait;

    /**
     * @var string $layerType The type of the layer.
     */
    protected $layerType;

    /**
     * ImageLayerParam constructor.
     *
     * @param string|LayerSource|mixed $source The source of the layer
     */
    public function __construct($source)
    {
        parent::__construct(ClassUtils::verifyInstance($source, LayerSource::class));
    }
}
