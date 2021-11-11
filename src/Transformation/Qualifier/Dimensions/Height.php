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

use Cloudinary\Transformation\Qualifier\BaseQualifier;

/**
 * Class Height
 */
class Height extends BaseQualifier
{
    const VALUE_CLASS = HeightQualifierMultiValue::class;
}
