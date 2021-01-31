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
 * Class Overlay
 *
 * @package Cloudinary\Transformation
 */
abstract class Overlay
{
    /**
     * @param $source
     *
     * @return ImageOverlay
     */
    public static function source($source)
    {
        return ClassUtils::verifyInstance($source, BaseLayerContainer::class, MediaOverlay::class);
    }

    /**
     * @param $source
     *
     * @return ImageOverlay
     */
    public static function imageSource($source)
    {
        return ClassUtils::verifyInstance($source, BaseLayerContainer::class, ImageOverlay::class);
    }

    /**
     * @param $source
     *
     * @return ImageOverlay
     */
    public static function videoSource($source)
    {
        return ClassUtils::verifyInstance($source, BaseLayerContainer::class, VideoOverlay::class);
    }
}
