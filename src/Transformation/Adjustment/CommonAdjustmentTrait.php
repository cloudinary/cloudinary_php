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
 * Trait CommonAdjustmentTrait
 *
 * @api
 */
trait CommonAdjustmentTrait
{
    /**
     * Adjusts the brightness.
     *
     * @param int $level The level of brightness (Range: -99 to 100, Server default: 80)
     *
     * @return EffectAction
     */
    public static function brightness($level = null)
    {
        return EffectAction::limited(Adjust::BRIGHTNESS, EffectRange::BRIGHTNESS, $level);
    }

    /**
     * Adjusts the contrast.
     *
     * @param int $level The level of contrast (Range: -100 to 100, Server default: 0)
     *
     * @return EffectAction
     */
    public static function contrast($level = null)
    {
        return EffectAction::limited(Adjust::CONTRAST, EffectRange::DEFAULT_RANGE, $level);
    }

    /**
     * Adjusts the color saturation.
     *
     * @param int $level The level of color saturation (Range: -100 to 100, Server default: 80).
     *
     * @return EffectAction
     */
    public static function saturation($level = null)
    {
        return EffectAction::limited(Adjust::SATURATION, EffectRange::DEFAULT_RANGE, $level);
    }

    /**
     * Adjusts the gamma level.
     *
     * @param int $level The level of gamma (Range: -50 to 150, Server default: 0).
     *
     * @return EffectAction
     */
    public static function gamma($level)
    {
        return EffectAction::limited(Adjust::GAMMA, EffectRange::SHIFTED_RANGE, $level);
    }

    /**
     * Applies any effect.
     *
     * This is a generic way to apply an effect.  For example, you could set $name to "gamma" and $args to 50, and this
     * would have the same effect as calling gamma(50).
     *
     * @param string $name The effect name.
     * @param mixed  ...$args The parameters of the effect.
     *
     * @return EffectAction
     */
    public static function generic($name, ...$args)
    {
        return EffectAction::named($name, ...$args);
    }
}
