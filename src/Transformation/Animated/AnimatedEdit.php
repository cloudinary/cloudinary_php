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
 * Action for editing an animated image.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/animated_images" target="_blank">
 * Animated images</a>
 *
 * @api
 */
class AnimatedEdit extends BaseAction
{
    use AnimatedEditTrait;
}
