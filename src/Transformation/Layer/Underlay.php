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
 * Class Underlay
 *
 * @package Cloudinary\Transformation
 */
abstract class Underlay
{
    /**
     * @param $source
     *
     * @return ImageOverlay
     */
    public static function source($source)
    {
        return ClassUtils::forceInstance($source, ImageOverlay::class)->setStackPosition(LayerStackPosition::UNDERLAY);
    }
}
