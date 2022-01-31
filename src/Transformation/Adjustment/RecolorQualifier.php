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

use Cloudinary\ClassUtils;

/**
 * Converts the colors of every pixel in an image based on the supplied color matrix, in which the value of each
 * color channel is calculated based on the values from all other channels (e.g. a 3x3 matrix for RGB, a 4x4 matrix
 * for RGBA or CMYK, etc).
 *
 * For every pixel in the image, take each color channel and adjust its value by the
 * specified values of the matrix to get a new value.
 *
 * @api
 */
class RecolorQualifier extends EffectQualifier
{
    /**
     * Recolor constructor.
     *
     * @param array|MatrixValue $colorMatrix
     */
    public function __construct(...$colorMatrix)
    {
        parent::__construct(Adjust::RECOLOR, ClassUtils::verifyVarArgsInstance($colorMatrix, MatrixValue::class));
    }
}
