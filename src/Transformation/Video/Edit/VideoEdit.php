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
 * Methods for editing a video.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/video_manipulation_and_delivery" target="_blank">
 * Video manipulation</a>
 *
 * @api
 */
abstract class VideoEdit
{
    use VideoEditBuilderTrait;
}
