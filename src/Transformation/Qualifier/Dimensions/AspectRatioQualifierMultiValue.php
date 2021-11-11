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

use Cloudinary\Transformation\Expression\ExpressionUtils;

/**
 * Class AspectRatioQualifierMultiValue.
 */
class AspectRatioQualifierMultiValue extends QualifierMultiValue
{
    /**
     * Normalize a given string.
     *
     * @param $value
     *
     * @return string
     */
    public function normalize($value)
    {
        if ((string)$value === AspectRatio::IGNORE_INITIAL) {
            return $value;
        }
        return ExpressionUtils::normalize($value);
    }
}
