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
 * Trait NamedTransformationQualifierTrait
 *
 * @api
 */
trait NamedTransformationQualifierTrait
{
    /**
     * Applies a pre-defined transformation to the asset.
     *
     * @param string $transformationName The name of the transformation.
     *
     * @return NamedTransformation
     *
     * @see \Cloudinary\Transformation\NamedTransformation
     */
    public static function namedTransformation($transformationName)
    {
        return new NamedTransformation($transformationName);
    }
}
