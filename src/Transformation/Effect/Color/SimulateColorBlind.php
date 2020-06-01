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
 * Class SimulateColorBlind
 */
class SimulateColorBlind extends ValueEffectParam
{
    /**
     * Color blind condition: deuteranopia (Server default).
     */
    const DEUTERANOPIA      = 'deuteranopia';

    /**
     * Color blind condition: protanopia.
     */
    const PROTANOPIA        = 'protanopia';

    /**
     * Color blind condition: tritanopia.
     */
    const TRITANOPIA        = 'tritanopia';

    /**
     * Color blind condition: tritanomaly.
     */
    const TRITANOMALY       = 'tritanomaly';

    /**
     * Color blind condition: deuteranomaly.
     */
    const DEUTERANOMALY     = 'deuteranomaly';

    /**
     * Color blind condition: cone_monochromacy.
     */
    const CONE_MONOCHROMACY = 'cone_monochromacy';

    /**
     * Color blind condition: rod_monochromacy.
     */
    const ROD_MONOCHROMACY  = 'rod_monochromacy';

    /**
     * SimulateColorBlind constructor.
     *
     * @param null $expression
     */
    public function __construct($expression = null)
    {
        parent::__construct(ColorEffect::SIMULATE_COLOR_BLIND, $expression);
    }
}
