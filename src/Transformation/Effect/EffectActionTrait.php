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

use Cloudinary\ArrayUtils;

/**
 * Trait EffectActionTrait
 *
 * @internal
 */
trait EffectActionTrait
{
    /**
     * @param       $name
     * @param mixed ...$args
     *
     * @return EffectAction
     */
    public static function named($name, ...$args)
    {
        return static::fromEffectQualifier(new EffectQualifier($name, ...$args));
    }

    /**
     * @param       $effectName
     * @param null  $value
     * @param mixed ...$args
     *
     * @return EffectAction
     */
    public static function valued($effectName, $value = null, ...$args)
    {
        return static::fromEffectQualifier(new ValueEffectQualifier($effectName, $value, ...$args));
    }

    /**
     * @param       $effectName
     * @param       $range
     * @param null  $value
     * @param mixed ...$args
     *
     * @return EffectAction
     */
    public static function limited($effectName, $range, $value = null, ...$args)
    {
        return static::fromEffectQualifier(new LimitedEffectQualifier($effectName, $range, $value, ...$args));
    }

    /**
     * @param       $effectName
     * @param       $range
     * @param null  $value
     * @param mixed ...$args
     *
     * @return LevelEffectAction
     */
    public static function withLevel($effectName, $range, $value = null, ...$args)
    {
        return new LevelEffectAction(new LevelEffectQualifier($effectName, $range, $value, ...$args));
    }

    /**
     * @param       $effectName
     * @param       $range
     * @param null  $value
     * @param mixed ...$args
     *
     * @return StrengthEffectAction
     */
    public static function withStrength($effectName, $range, $value = null, ...$args)
    {
        return new StrengthEffectAction(new StrengthEffectQualifier($effectName, $range, $value, ...$args));
    }

    /**
     * @param       $effectName
     * @param       $range
     * @param null  $value
     * @param mixed ...$args
     *
     * @return BlendEffectAction
     */
    public static function withBlend($effectName, $range, $value = null, ...$args)
    {
        return new BlendEffectAction(new BlendEffectQualifier($effectName, $range, $value, ...$args));
    }

    /**
     * @param       $effectName
     * @param       $range
     * @param null  $value
     * @param mixed ...$args
     *
     * @return ThresholdEffectAction
     */
    public static function withThreshold($effectName, $range, $value = null, ...$args)
    {
        return new ThresholdEffectAction(new ThresholdEffectQualifier($effectName, $range, $value, ...$args));
    }

    /**
     * @param       $effectName
     * @param null  $value
     * @param mixed ...$args
     *
     * @return DurationEffectAction
     */
    public static function withDuration($effectName, $value = null, ...$args)
    {
        return new DurationEffectAction(new DurationEffectQualifier($effectName, $value, ...$args));
    }

    /**
     * @param       $effectName
     * @param null  $value
     * @param mixed ...$args
     *
     * @return ToleranceEffectAction
     */
    public static function withTolerance($effectName, $value = null, ...$args)
    {
        return new ToleranceEffectAction(new ToleranceEffectQualifier($effectName, $value, ...$args));
    }

    /**
     * @param array $qualifiers
     *
     * @return EffectAction
     */
    public static function fromParams(array $qualifiers)
    {
        $effectName = ArrayUtils::pop($qualifiers, 0);

        return static::fromEffectQualifier(new EffectQualifier($effectName, ...$qualifiers));
    }

    /**
     * @param EffectQualifier $effectQualifier
     *
     * @return EffectAction
     */
    public static function fromEffectQualifier(EffectQualifier $effectQualifier)
    {
        return new EffectAction($effectQualifier);
    }
}
