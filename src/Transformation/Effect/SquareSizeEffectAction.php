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
 * Class SquareSizeEffectAction
 */
class SquareSizeEffectAction extends EffectAction
{
    /**
     * Setter of the effect square size.
     *
     * @param int $squareSize The square size to set.
     *
     * @return $this
     */
    public function squareSize($squareSize)
    {
        $this->qualifiers[EffectQualifier::getName()]->squareSize($squareSize);

        return $this;
    }
}
