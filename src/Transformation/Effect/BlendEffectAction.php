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
 * Class BlendEffectAction
 */
class BlendEffectAction extends EffectAction
{
    /**
     * Setter of the effect blend.
     *
     * @param int $blend The blend percentage to set.
     *
     * @return $this
     */
    public function blend($blend)
    {
        $this->qualifiers[EffectQualifier::getName()]->blend($blend);

        return $this;
    }
}
