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

/**
 * Trait OpacityQualifierTrait
 *
 * @api
 */
trait OpacityQualifierTrait
{
    /**
     * Adjusts the opacity of the image and makes it semi-transparent.
     *
     * @param int $level The level of opacity. 100 means opaque, while 0 is completely transparent
     *                   (Range: 0 to 100).
     *
     * @return Opacity
     */
    public static function opacity($level)
    {
        return new Opacity($level);
    }
}
