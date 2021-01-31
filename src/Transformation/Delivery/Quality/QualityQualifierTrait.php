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
 * Trait QualityQualifierTrait
 *
 * @api
 */
trait QualityQualifierTrait
{
    /**
     * Controls the compression quality for images and videos.
     *
     * Reducing the quality is a trade-off between visual quality and file size.
     *
     * @param int         $level  The level of the quality. (Range 1 to 100)
     * @param null|string $preset A set level of automatic quality.  Use the constants defined in the QualityQualifier
     *                            class.
     *
     * @return QualityQualifier
     *
     * @see \Cloudinary\Transformation\QualityQualifier
     */
    public static function quality($level, $preset = null)
    {
        return new QualityQualifier($level, $preset);
    }
}
