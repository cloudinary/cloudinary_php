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
 * Trait GravityParamTrait
 *
 * @api
 */
trait GravityParamTrait
{
    /**
     * Determines which part of the image to keep when any part of the image is cropped. For overlays, this setting
     * determines where to place the overlay.
     *
     * @param mixed $gravity The area of the image.  Use the constants defined in any of the classes that extend
     *                        GravityParam, such as CompassGravity or ObjectGravity.
     *
     * @return GravityParam
     */
    public static function gravity($gravity)
    {
        return ClassUtils::verifyInstance($gravity, GravityParam::class);
    }
}
