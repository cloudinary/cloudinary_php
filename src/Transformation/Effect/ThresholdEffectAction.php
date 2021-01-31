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
 * Class ThresholdEffectAction
 */
class ThresholdEffectAction extends EffectAction
{
    /**
     * Setter of the effect threshold.
     *
     * @param int $threshold The threshold to set.
     *
     * @return $this
     */
    public function threshold($threshold)
    {
        $this->qualifiers[EffectQualifier::getName()]->threshold($threshold);

        return $this;
    }
}
