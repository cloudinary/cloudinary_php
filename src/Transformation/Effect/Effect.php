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
 * Defines effects that you can apply to transform your assets.
 *
 * **Learn more**: <a
 * href=https://cloudinary.com/documentation/image_transformations#applying_image_effects_and_filters target="_blank">
 * Image effects</a> |
 * <a href=https://cloudinary.com/documentation/video_manipulation_and_delivery#video_effects target="_blank">
 * Video effects</a>
 *
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
     * Creates effect from an array of qualifiers.
     *
     * @param string|array $qualifiers The effect qualifiers.
     *
     * @return EffectAction
     */
    public static function fromParams($qualifiers)
    {
        return EffectAction::fromParams(ArrayUtils::build($qualifiers));
    }
}
