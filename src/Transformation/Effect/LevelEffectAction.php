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
 * Class LevelEffectAction
 */
class LevelEffectAction extends EffectAction
{
    /**
     * Setter of the effect level.
     *
     * @param int $level The level to set.
     *
     * @return $this
     */
    public function level($level)
    {
        $this->qualifiers[EffectQualifier::getName()]->level($level);

        return $this;
    }
}
