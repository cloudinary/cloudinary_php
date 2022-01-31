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
 * Class SquareSizeEffectQualifier
 *
 * This class is used for all effects that have a square size value.
 *
 * @internal
 */
class SquareSizeEffectQualifier extends LimitedEffectQualifier
{
    /**
     * Setter of the effect square size.
     *
     * @param int $squareSize The square size to set.
     *
     * @return SquareSizeEffectQualifier
     *
     * @internal
     */
    public function squareSize($squareSize)
    {
        $this->setEffectValue($squareSize);

        return $this;
    }
}
