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
 * Interface ImageFlagInterface
 */
interface ImageFlagInterface
{
    const ANIMATED              = 'animated';
    const ANY_FORMAT            = 'any_format';
    const ANIMATED_PNG          = 'apng';
    const ANIMATED_WEBP         = 'awebp';
    const CLIP                  = 'clip';
    const CLIP_EVEN_ODD         = 'clip_evenodd';
    const LOSSY                 = 'lossy';
    const PRESERVE_TRANSPARENCY = 'preserve_transparency';
    const PNG8                  = 'png8';
    const PNG24                 = 'png24';
    const PNG32                 = 'png32';
    const PROGRESSIVE           = 'progressive';
    const RASTERIZE             = 'rasterize';
    const SANITIZE              = 'sanitize';
    const STRIP_PROFILE         = 'strip_profile';
    const TIFF8_LZW             = 'tiff8_lzw';
    const IGNORE_MASK_CHANNELS  = 'ignore_mask_channels';
}
