<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Argument;

use Cloudinary\Transformation\ParameterMultiValue;

/**
 * Defines how to rotate an image.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/image_transformations#rotating_images" target="_blank">
 * Rotate an image</a>
 *
 * @api
 */
class Degree extends ParameterMultiValue
{
    const VALUE_DELIMITER = '.';

    use AngleTrait;

    const AUTO_RIGHT      = 'auto_right';
    const AUTO_LEFT       = 'auto_left';
    const VERTICAL_FLIP   = 'vflip';
    const HORIZONTAL_FLIP = 'hflip';
    const IGNORE          = 'ignore';

    const DEG_90          = '90';
    const DEG_180         = '180';
    const DEG_270         = '270';
}
