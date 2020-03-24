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
 * Class LutLayer
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
