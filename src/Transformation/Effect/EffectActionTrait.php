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
        return static::fromEffectParam(new EffectParam($name, ...$args));
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
        return static::fromEffectParam(new ValueEffectParam($effectName, $value, ...$args));
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
        return static::fromEffectParam(new LimitedEffectParam($effectName, $range, $value, ...$args));
    }

    /**
     * @param array $params
     *
     * @return EffectAction
     */
    public static function fromParams(array $params)
    {
        $effectName = ArrayUtils::pop($params, 0);

        return static::fromEffectParam(new EffectParam($effectName, ...$params));
    }

    /**
     * @param EffectParam $effectParam
     *
     * @return EffectAction
     */
    public static function fromEffectParam(EffectParam $effectParam)
    {
        return new EffectAction($effectParam);
    }
}
