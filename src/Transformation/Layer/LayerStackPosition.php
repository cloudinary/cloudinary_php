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
 * Defines the position of a layer - overlay or underlay.
 *
 * **Learn more**: <a
 * href=https://cloudinary.com/documentation/image_transformations#image_and_text_overlays target="_blank">
 * Applying overlays to images</a> |
 * <a href=https://cloudinary.com/documentation/video_manipulation_and_delivery#adding_image_overlays target="_blank">
 * Applying overlays to videos</a>
 *
 * @api
 */
class LayerStackPosition
{
    const OVERLAY = 'overlay';
    const UNDERLAY = 'underlay';
}
