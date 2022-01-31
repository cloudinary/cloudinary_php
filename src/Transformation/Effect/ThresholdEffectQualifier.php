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
 * Class ThresholdEffectQualifier
 *
 * This class is used for all effects that have a threshold value.
 *
 * @internal
 */
class ThresholdEffectQualifier extends LimitedEffectQualifier
{
    /**
     * Setter of the effect threshold.
     *
     * @param int $threshold The threshold to set.
     *
     * @return ThresholdEffectQualifier
     *
     * @internal
     */
    public function threshold($threshold)
    {
        $this->setEffectValue($threshold);

        return $this;
    }
}
