<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Qualifier\Dimensions;

use Cloudinary\Transformation\Qualifier\BaseExpressionQualifier;

/**
 * Class Width
 *
 * The required width of a transformed image or an overlay. Can be specified separately or together with the height
 * value. To set the width of the image to the initial width of the original image use the value 'iw' ('ow' has been
 * deprecated).
 */
class Width extends BaseExpressionQualifier
{
}
