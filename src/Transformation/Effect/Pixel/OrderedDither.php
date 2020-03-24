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
 * Class OrderedDither
 */
class OrderedDither extends LimitedEffectParam
{
    const THRESHOLD_1X1_NON_DITHER  = 0;
    const CHECKERBOARD_2X1_DITHER   = 1;
    const ORDERED_2X2_DISPERSED     = 2;
    const ORDERED_3X3_DISPERSED     = 3;
    const ORDERED_4X4_DISPERSED     = 4;
    const ORDERED_8X8_DISPERSED     = 5;
    const HALFTONE_4X4_ANGLED       = 6;
    const HALFTONE_6X6_ANGLED       = 7;
    const HALFTONE_8X8_ANGLED       = 8;
    const HALFTONE_4X4_ORTHOGONAL   = 9;
    const HALFTONE_6X6_ORTHOGONAL   = 10;
    const HALFTONE_8X8_ORTHOGONAL   = 11;
    const HALFTONE_16X16_ORTHOGONAL = 12;
    const CIRCLES_5X5_BLACK         = 13;
    const CIRCLES_5X5_WHITE         = 14;
    const CIRCLES_6X6_BLACK         = 15;
    const CIRCLES_6X6_WHITE         = 16;
    const CIRCLES_7X7_BLACK         = 17;
    const CIRCLES_7X7_WHITE         = 18;

    /**
     * OrderedDither constructor.
     *
     * @param       $level
     * @param mixed ...$params
     */
    public function __construct($level, ...$params)
    {
        parent::__construct(PixelEffect::ORDERED_DITHER, EffectRange::ORDERED_DITHER, $level, $params);
    }
}
