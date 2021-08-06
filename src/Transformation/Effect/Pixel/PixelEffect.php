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
 * Class Effect
 */
abstract class PixelEffect
{
    // Common
    const BLUR     = 'blur';
    const VIGNETTE = 'vignette';

    // Image
    const BLUR_FACES        = 'blur_faces';
    const BLUR_REGION       = 'blur_region';
    const PIXELATE          = 'pixelate';
    const PIXELATE_REGION   = 'pixelate_region';
    const PIXELATE_FACES    = 'pixelate_faces';
    const ORDERED_DITHER    = 'ordered_dither';
    const GRADIENT_FADE     = 'gradient_fade';
    const MAKE_TRANSPARENT  = 'make_transparent';
    const REMOVE_BACKGROUND = 'bgremoval';
    const CUT_OUT           = 'cut_out';

    use PixelEffectTrait;
    use ImagePixelEffectTrait;
}
