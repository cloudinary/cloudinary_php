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
 * Defines how to transform an image.
 *
 * **Learn more**: <a
 * href=https://cloudinary.com/documentation/image_transformations target="_blank">
 * Image transformations</a>
 *
 * @api
 */
class ImageTransformation extends CommonTransformation implements ImageTransformationInterface
{
    use ImageTransformationTrait;
}
