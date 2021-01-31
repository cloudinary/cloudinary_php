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
 * Trait ImageColorEffectTrait
 *
 * @api
 */
trait ImageColorEffectTrait
{
    /**
     * Converts the image to black and white.
     *
     * @param int $threshold The balance between black (100) and white (0).  (Range: 0 to 100, Server default: 50)
     *
     * @return ThresholdEffectAction
     */
    public static function blackWhite($threshold = null)
    {
        return EffectAction::withThreshold(ColorEffect::BLACKWHITE, EffectRange::PERCENT, $threshold);
    }

    /**
     * Colorizes the image.
     *
     * @param int    $level The strength of the color. (Range: 0 to 100, Server default: 100)
     * @param string $color The color to use for colorization.  Specify HTML name or RGB hex code.
     *                      (Server default: gray)
     *
     * @return Colorize
     */
    public static function colorize($level = null, $color = null)
    {
        return (new Colorize(new LevelEffectQualifier(ColorEffect::COLORIZE, EffectRange::PERCENT, $level)))
            ->color($color);
    }

    /**
     * Converts the image to gray-scale (multiple shades of gray).
     *
     * @return EffectAction
     */
    public static function grayscale()
    {
        return EffectAction::named(ColorEffect::GRAYSCALE);
    }

    /**
     * Negates the image colors (negative).
     *
     * @return EffectAction
     */
    public static function negate()
    {
        return EffectAction::named(ColorEffect::NEGATE);
    }

    /**
     * Changes the color scheme of the image to sepia.
     *
     * @param int $level The level of sepia to apply. (Range: 1 to 100, Server default: 80)
     *
     * @return LevelEffectAction
     */
    public static function sepia($level = null)
    {
        return EffectAction::withLevel(ColorEffect::SEPIA, EffectRange::PERCENT, $level);
    }

    /**
     * Applies stripes to the image to help people with common color-blind conditions to differentiate between colors
     * that are similar for them.
     *
     * You can replace colors using the xRay() method of the \Cloudinary\Transformation\AssistColorBlind class.
     *
     * @param int $strength The strength of the stripes.  (Range: 1 to 100, Server default: 10)
     *
     * @return AssistColorBlind
     *
     * @see \Cloudinary\Transformation\AssistColorBlind
     *
     */
    public static function assistColorBlind($strength = null)
    {
        return new AssistColorBlind($strength);
    }

    /**
     * Simulates the way an image would appear to someone with the specified color blind condition.
     *
     * For a list of supported color blind conditions see the \Cloudinary\Transformation\SimulateColorBlind class.
     *
     * @param string $condition The color blind condition. Use the constants defined in the SimulateColorBlind class.
     *
     * @return SimulateColorBlind
     *
     * @see \Cloudinary\Transformation\SimulateColorBlind
     *
     */
    public static function simulateColorBlind($condition = null)
    {
        return new SimulateColorBlind($condition);
    }
}
