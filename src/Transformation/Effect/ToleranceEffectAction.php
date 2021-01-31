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
 * Class ToleranceEffectAction
 */
class ToleranceEffectAction extends EffectAction
{
    /**
     * Setter of the effect tolerance level.
     *
     * @param int $tolerance The level of tolerance to set.
     *
     * @return $this
     */
    public function tolerance($tolerance)
    {
        $this->qualifiers[EffectQualifier::getName()]->tolerance($tolerance);

        return $this;
    }
}
