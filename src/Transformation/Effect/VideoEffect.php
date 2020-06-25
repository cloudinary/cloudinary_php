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
 * Defines how to manipulate the appearance of a video.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/video_manipulation_and_delivery#video_effects" target="_blank">
 * Video effects</a>
 *
 * @api
 */
abstract class VideoEffect
{
    use CommonEffectTrait;
    use VideoEffectTrait;
}
