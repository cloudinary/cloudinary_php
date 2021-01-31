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
 * Class StrengthEffectAction
 */
class StrengthEffectAction extends EffectAction
{
    /**
     * Setter of the effect strength.
     *
     * @param int $strength The strength to set.
     *
     * @return $this
     */
    public function strength($strength)
    {
        $this->qualifiers[EffectQualifier::getName()]->strength($strength);

        return $this;
    }
}
