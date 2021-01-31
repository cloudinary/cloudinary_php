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
 * Trait PixelEffectTrait
 *
 * @api
 */
trait PixelEffectTrait
{
    /**
     * Applies a blurring filter to the asset.
     *
     * @param int $strength The strength of the blur. (Range: 1 to 2000, Server default: 100)
     *
     * @return Blur
     */
    public static function blur($strength = null)
    {
        return new Blur($strength);
    }

    /**
     * Applies a vignette effect.
     *
     * @param int $strength The strength of the vignette. (Range: 0 to 100, Server default: 20)
     *
     * @return StrengthEffectAction
     */
    public static function vignette($strength = null)
    {
        return EffectAction::withStrength(PixelEffect::VIGNETTE, EffectRange::PERCENT, $strength);
    }
}
