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
 * Trait LayerParamTrait
 *
 * @api
 */
trait LayerParamTrait
{
    /**
     * Sets the overlay source.
     *
     * @param string|mixed $source The source of the layer.
     *
     * @return ImageLayer
     */
    public static function overlay($source)
    {
        return ClassUtils::verifyInstance($source, BaseLayer::class, ImageLayer::class);
    }

    /**
     * Sets the underlay source.
     *
     * @param string|mixed $source The source of the layer.
     *
     * @return ImageLayer
     */
    public static function underlay($source)
    {
        $layer = ClassUtils::verifyInstance($source, BaseLayer::class, ImageLayer::class);

        return $layer->setStackPosition(LayerStackPosition::UNDERLAY);
    }
}
