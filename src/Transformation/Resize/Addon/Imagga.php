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
 * Class Imagga
 *
 * When using the Imagga add-on, your images will be scaled and cropped based on automatically calculated areas of
 * interest of each specific photo
 */
class Imagga extends BaseResizeAction
{
    use ImaggaTrait;
}
