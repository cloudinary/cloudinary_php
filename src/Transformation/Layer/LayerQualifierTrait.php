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
 * Trait LayerQualifierTrait
 *
 * @api
 */
trait LayerQualifierTrait
{
    /**
     * Sets the overlay source.
     *
     * @param string|mixed $source The source of the layer.
     *
     * @return ImageSource
     */
    public static function overlay($source)
    {
        return ClassUtils::verifyInstance($source, BaseSource::class, ImageSource::class);
    }

    /**
     * Sets the underlay source.
     *
     * @param string|mixed $source The source of the layer.
     *
     * @return ImageSource
     */
    public static function underlay($source)
    {
        $layer = ClassUtils::verifyInstance($source, BaseSource::class, ImageSource::class);

        return $layer->setStackPosition(LayerStackPosition::UNDERLAY);
    }
}
