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
 * Class Blur
 */
class Blur extends StrengthEffectAction
{
    const REGION = PixelEffect::BLUR_REGION;
    const FACES  = PixelEffect::BLUR_FACES;

    /**
     * Pixelate constructor.
     *
     * @param       $strength
     * @param mixed ...$args
     */
    public function __construct($strength, ...$args)
    {
        parent::__construct(
            new StrengthEffectQualifier(PixelEffect::BLUR, EffectRange::PIXEL, $strength),
            ...$args
        );
    }

    use RegionEffectTrait;
}
