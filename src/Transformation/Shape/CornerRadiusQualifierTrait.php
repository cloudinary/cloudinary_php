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
 * Trait CornerRadiusQualifierTrait
 *
 * @api
 */
trait CornerRadiusQualifierTrait
{
    /**
     * Rounds the corners of an asset.
     *
     * @param array $value The radius of the corner(s). See CornerRadius class for details.
     *
     * @return CornerRadius
     *
     * @see CornerRadius
     */
    public static function cornerRadius(...$value)
    {
        return new CornerRadius(...$value);
    }
}
