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
 * Trait AngleQualifierTrait
 *
 * @api
 */
trait AngleQualifierTrait
{
    /**
     * Sets the angle of the rotation of the asset.
     *
     * @param int|string|array|mixed $degree The rotation degree and/or mode.
     *
     * @return Angle
     */
    public static function angle(...$degree)
    {
        return new Angle(...$degree);
    }
}
