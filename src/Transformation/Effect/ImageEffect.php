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
 * Defines effects that you can apply to transform your images.
 *
 * **Learn more**: <a
 * href=https://cloudinary.com/documentation/image_transformations#applying_image_effects_and_filters target="_blank">
 * Image effects</a>
 *
 * @api
 */
abstract class ImageEffect
{
    use CommonEffectTrait;
    use ImageEffectTrait;
}
