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
 * Interface LayerFlagInterface
 */
interface LayerFlagInterface
{
    const CUTTER                 = 'cutter';
    const LAYER_APPLY            = 'layer_apply';
    const NO_OVERFLOW            = 'no_overflow';
    const REGION_RELATIVE        = 'region_relative';
    const RELATIVE               = 'relative';
    const REPLACE_IMAGE          = 'replace_image';
    const SPLICE                 = 'splice';
    const TEXT_NO_TRIM           = 'text_no_trim';
    const TEXT_DISALLOW_OVERFLOW = 'text_disallow_overflow';
    const TILED                  = 'tiled';
}
