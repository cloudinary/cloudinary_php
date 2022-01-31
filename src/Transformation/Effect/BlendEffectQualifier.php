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
 * Class BlendEffectQualifier
 *
 * This class is used for all effects that have a blend percentage value.
 *
 * @internal
 */
class BlendEffectQualifier extends LimitedEffectQualifier
{
    /**
     * Setter of the effect blend percentage.
     *
     * @param int $blend The blend percentage to set.
     *
     * @return BlendEffectQualifier
     *
     * @internal
     */
    public function blend($blend)
    {
        $this->setEffectValue($blend);

        return $this;
    }
}
