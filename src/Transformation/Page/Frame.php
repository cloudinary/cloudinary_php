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
 * Represents a frame in an animated GIF.
 *
 * **Learn more**: <a href=https://cloudinary.com/documentation/animated_images#deliver_a_single_frame
 * target="_blank">
 * Deliver a single frame of an animated image</a>
 *
 * @api
 */
abstract class Frame
{
    use PageNumberTrait;

    /**
     * Internal named constructor.
     *
     * @param $value
     *
     * @return PageQualifier
     *
     * @internal
     */
    public static function createWithPageQualifier(...$value)
    {
        return new PageQualifier(...$value);
    }
}
