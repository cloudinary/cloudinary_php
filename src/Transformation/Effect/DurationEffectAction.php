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
 * Class DurationEffectAction
 */
class DurationEffectAction extends EffectAction
{
    /**
     * Setter of the effect duration.
     *
     * @param int $duration The duration to set.
     *
     * @return $this
     */
    public function duration($duration)
    {
        $this->qualifiers[EffectQualifier::getName()]->duration($duration);

        return $this;
    }
}
