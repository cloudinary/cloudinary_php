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
 * Indicates that the requested dimensions for the resize are percentage values relative to another asset or element,
 * rather than pixel values.
 *
 * @api
 */
abstract class ResizeMode
{
    use ResizeModeTrait;
}
