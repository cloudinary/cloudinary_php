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
 * Defines transformations for delivering your assets without changing the visual or audio experience
 * for the end user.
 *
 * **Learn more**: <a href=https://cloudinary.com/documentation/image_delivery_options target="_blank">
 * Media delivery</a>
 *
 * @api
 */
abstract class Delivery
{
    use DeliveryBuilderTrait;
}
