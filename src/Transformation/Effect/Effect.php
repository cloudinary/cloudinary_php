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
 * Class Effect
 * @api
 */
abstract class Effect
{
    use CommonEffectTrait;
    use ImageEffectTrait;
    use VideoEffectTrait;

    /**
     * A user defined generic effect.
     *
     * @param string $name    The effect name.
     * @param mixed  ...$args Optional effect arguments.
     *
     * @return EffectAction
     */
    public static function generic($name, ...$args)
    {
        return EffectAction::named($name, ...$args);
    }

    /**
     * Creates effect from an array of parameters.
     *
     * @param string|array $params The effect parameters.
     *
     * @return EffectAction
     */
    public static function fromParams($params)
    {
        return EffectAction::fromParams(ArrayUtils::build($params));
    }
}
