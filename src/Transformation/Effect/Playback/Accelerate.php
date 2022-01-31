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
 * Class Accelerate
 */
class Accelerate extends EffectAction
{
    /**
     * Loop constructor.
     *
     * @param int   $rate    The percentage change of speed. Positive numbers speed up the playback, negative numbers
     *                       slow down the playback (Range: -50 to 100, Server default: 0).
     * @param mixed ...$args Additional arguments.
     */
    public function __construct($rate, ...$args)
    {
        parent::__construct(
            new LimitedEffectQualifier(PlaybackEffect::ACCELERATE, EffectRange::EXTENDED_PERCENT, $rate, ...$args)
        );
    }

    /**
     * Setter of the acceleration rate.
     *
     * @param int $rate The percentage change of speed. Positive numbers speed up the playback, negative numbers
     *                  slow down the playback (Range: -50 to 100, Server default: 0).
     *
     * @return $this
     */
    public function rate($rate)
    {
        $this->qualifiers[EffectQualifier::getName()]->setEffectValue($rate);

        return $this;
    }
}
