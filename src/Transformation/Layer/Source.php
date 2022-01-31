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
 * Defines the asset to use as the layered file in an overlay or underlay.
 *
 *  **Learn more**:
 * <a href=https://cloudinary.com/documentation/image_transformations#image_and_text_overlays
 * target="_blank">Applying layers to images</a> |
 * <a href=https://cloudinary.com/documentation/video_manipulation_and_delivery#adding_image_overlays
 * target="_blank">Applying layers to videos</a>
 *
 * @api
 */
abstract class Source
{
    use ImageSourceTrait;
    use VideoSourceTrait;
}
