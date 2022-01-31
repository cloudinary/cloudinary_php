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
 * Determines how to crop, scale, and/or zoom the delivered asset according to the requested dimensions.
 *
 * **Learn more**:
 * <a href=https://cloudinary.com/documentation/image_transformations#resizing_and_cropping_images
 * target="_blank">Resizing images</a> |
 * <a href=https://cloudinary.com/documentation/video_manipulation_and_delivery#resizing_and_cropping_videos
 * target="_blank">Resizing videos</a>
 *
 * @api
 */
class Resize extends BaseResizeAction
{
    use ResizeTrait;
}
