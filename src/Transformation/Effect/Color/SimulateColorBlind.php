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
class SimulateColorBlind extends ValueEffectQualifier
{
    /**
     * Color blind condition: deuteranopia (Server default).
     */
    const DEUTERANOPIA = 'deuteranopia';

    /**
     * Color blind condition: protanopia.
     */
    const PROTANOPIA = 'protanopia';

    /**
     * Color blind condition: tritanopia.
     */
    const TRITANOPIA = 'tritanopia';

    /**
     * Color blind condition: tritanomaly.
     */
    const TRITANOMALY = 'tritanomaly';

    /**
     * Color blind condition: deuteranomaly.
     */
    const DEUTERANOMALY = 'deuteranomaly';

    /**
     * Color blind condition: cone_monochromacy.
     */
    const CONE_MONOCHROMACY = 'cone_monochromacy';

    /**
     * Color blind condition: rod_monochromacy.
     */
    const ROD_MONOCHROMACY = 'rod_monochromacy';

    /**
     * SimulateColorBlind constructor.
     *
     * @param null $condition
     */
    public function __construct($condition = null)
    {
        parent::__construct(ColorEffect::SIMULATE_COLOR_BLIND);

        $this->condition($condition);
    }

    /**
     * Sets the color blind condition.
     *
     * @param string $condition The color blind condition to simulate
     *
     * @return SimulateColorBlind
     */
    public function condition($condition)
    {
        $this->setEffectValue($condition);

        return $this;
    }

    /**
     * Color blind condition: deuteranopia (Server default).
     *
     * @return string
     */
    public static function deuteranopia()
    {
        return self::DEUTERANOPIA;
    }

    /**
     * Color blind condition: protanopia.
     *
     * @return string
     */
    public static function protanopia()
    {
        return self::PROTANOPIA;
    }

    /**
     * Color blind condition: tritanopia
     *
     * @return string
     */
    public static function tritanopia()
    {
        return self::TRITANOPIA;
    }

    /**
     * Color blind condition: tritanomaly
     *
     * @return string
     */
    public static function tritanomaly()
    {
        return self::TRITANOMALY;
    }

    /**
     * Color blind condition: deuteranomaly
     *
     * @return string
     */
    public static function deuteranomaly()
    {
        return self::DEUTERANOMALY;
    }

    /**
     * Color blind condition: cone monochromacy
     *
     * @return string
     */
    public static function coneMonochromacy()
    {
        return self::CONE_MONOCHROMACY;
    }

    /**
     * Color blind condition: rod monochromacy
     *
     * @return string
     */
    public static function rodMonochromacy()
    {
        return self::ROD_MONOCHROMACY;
    }
}
