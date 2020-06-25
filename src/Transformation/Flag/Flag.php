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
 * Defines flags that you can use to alter the default transformation behavior.
 *
 * **Learn more**:
 * <a href="https://cloudinary.com/documentation/transformation_flags" target="_blank">
 * Image transformation flags</a> |
 * <a href="https://cloudinary.com/documentation/video_transformation_reference#video_transformation_flags"
 * target="_blank">Video transformation flags</a>
 *
 * @api
 */
abstract class Flag implements CommonFlagInterface, LayerFlagInterface, ImageFlagInterface, VideoFlagInterface
{
    use CommonFlagTrait;
    use ImageFlagTrait;
    use LayerFlagTrait;
    use VideoFlagTrait;
}
