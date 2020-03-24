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
 * Class MiscEffect
 */
abstract class MiscEffect
{
    const ARTISTIC_FILTER  = 'art';
    const STYLE_TRANSFER   = 'style_transfer';
    const CARTOONIFY       = 'cartoonify';
    const OIL_PAINT        = 'oil_paint';
    const RED_EYE          = 'redeye';
    const ADVANCED_RED_EYE = 'adv_redeye';
    const VECTORIZE        = 'vectorize';
    const OUTLINE          = 'outline';
}
