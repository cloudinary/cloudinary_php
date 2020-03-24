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
 * Class LutLayerParam
 */
class LutLayerParam extends BaseLayerParam
{
    /**
     * @var string $layerType The type of the layer.
     */
    protected $layerType = 'lut';

    /**
     * LutLayerParam constructor.
     *
     * @param $lutId
     */
    public function __construct($lutId)
    {
        parent::__construct();

        $this->lut($lutId);
    }

    /**
     * Sets the lut source.
     *
     * @param string|LayerSource $lutId The public ID of the LUT asset.
     *
     * @return $this
     */
    public function lut($lutId)
    {
        $this->value->setValue(ClassUtils::verifyInstance($lutId, LayerSource::class));

        return $this;
    }
}
