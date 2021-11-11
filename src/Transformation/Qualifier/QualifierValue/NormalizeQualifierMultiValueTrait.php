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
 * Trait NormalizeQualifierMultiValueTrait
 */
trait NormalizeQualifierMultiValueTrait
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
        return ExpressionUtils::normalize($value);
    }
}
