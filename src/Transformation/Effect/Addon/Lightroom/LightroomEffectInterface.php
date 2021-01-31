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
 * Interface LightroomEffectInterface
 */
interface LightroomEffectInterface
{
    const CONTRAST              = 'contrast';
    const SATURATION            = 'saturation';
    const VIGNETTE_AMOUNT       = 'vignetteamount';
    const VIBRANCE              = 'vibrance';
    const HIGHLIGHTS            = 'highlights';
    const SHADOWS               = 'shadows';
    const WHITES                = 'whites';
    const BLACKS                = 'blacks';
    const CLARITY               = 'clarity';
    const DEHAZE                = 'dehaze';
    const TEXTURE               = 'texture';
    const SHARPNESS             = 'sharpness';
    const COLOR_NOISE_REDUCTION = 'colornoisereduction';
    const NOISE_REDUCTION       = 'noisereduction';
    const SHARPEN_DETAIL        = 'sharpendetail';
    const SHARPEN_EDGE_MASKING  = 'sharpenedgemasking';
    const EXPOSURE              = 'exposure';
    const SHARPEN_RADIUS        = 'sharpenradius';
    const WHITE_BALANCE         = 'whitebalance';
    const XMP                   = 'xmp';
}
