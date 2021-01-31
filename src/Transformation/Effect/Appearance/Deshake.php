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
 * Class Deshake
 *
 * Removes small motion shifts from the video. with a maximum extent of movement in the horizontal and vertical
 * direction of 32 pixels
 */
class Deshake extends EffectAction
{
    /**
     * Deshake constructor.
     *
     * @param int   $shakeStrength The maximum number of pixels in the horizontal and vertical direction that will be
     *                             addressed. (Possible values: 16, 32, 48, 64. Server default: 16)
     * @param mixed ...$args       Additional arguments.
     */
    public function __construct($shakeStrength, ...$args)
    {
        parent::__construct(
            new LimitedEffectQualifier(AppearanceEffect::DESHAKE, EffectRange::DESHAKE, $shakeStrength, ...$args)
        );
    }

    /**
     * Setter of the shake strength.
     *
     * @param int $shakeStrength   The maximum number of pixels in the horizontal and vertical direction that will be
     *                             addressed. (Possible values: 16, 32, 48, 64. Server default: 16)
     *
     * @return $this
     */
    public function shakeStrength($shakeStrength)
    {
        $this->qualifiers[EffectQualifier::getName()]->setEffectValue($shakeStrength);

        return $this;
    }
}
