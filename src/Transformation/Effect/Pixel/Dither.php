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
 * Class Dither
 */
class Dither extends LimitedEffectQualifier
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
     * Dither constructor.
     *
     * @param       $type
     */
    public function __construct($type)
    {
        parent::__construct(PixelEffect::ORDERED_DITHER, EffectRange::ORDERED_DITHER);

        $this->type($type);
    }

    /**
     * Sets the type of the dither.
     *
     * @param int $type The type.
     *
     * @return Dither
     */
    public function type($type)
    {
        return $this->setEffectValue($type);
    }

    /**
     * Dither type threshold 1x1 non dither.
     *
     * @return int
     */
    public static function threshold1x1NonDither()
    {
        return self::THRESHOLD_1X1_NON_DITHER;
    }

    /**
     * Dither type checkerboard 2x1 dither.
     *
     * @return int
     */
    public static function checkerboard2x1Dither()
    {
        return self::CHECKERBOARD_2X1_DITHER;
    }

    /**
     * Dither type ordered 2x2 dispersed.
     *
     * @return int
     */
    public static function ordered2x2Dispersed()
    {
        return self::ORDERED_2X2_DISPERSED;
    }

    /**
     * Dither type ordered 3x3 dispersed.
     *
     * @return int
     */
    public static function ordered3x3Dispersed()
    {
        return self::ORDERED_3X3_DISPERSED;
    }

    /**
     * Dither type ordered 4x4 dispersed.
     *
     * @return int
     */
    public static function ordered4x4Dispersed()
    {
        return self::ORDERED_4X4_DISPERSED;
    }

    /**
     * Dither type ordered 8x8 dispersed.
     *
     * @return int
     */
    public static function ordered8x8Dispersed()
    {
        return self::ORDERED_8X8_DISPERSED;
    }

    /**
     * Dither type ordered 8x8 dispersed.
     *
     * @return int
     */
    public static function halftone4x4Angled()
    {
        return self::HALFTONE_4X4_ANGLED;
    }

    /**
     * Dither type halftone 6x6 angled.
     *
     * @return int
     */
    public static function halftone6x6Angled()
    {
        return self::HALFTONE_6X6_ANGLED;
    }

    /**
     * Dither type halftone 8x8 angled.
     *
     * @return int
     */
    public static function halftone8x8Angled()
    {
        return self::HALFTONE_8X8_ANGLED;
    }

    /**
     * Dither type halftone 4x4 orthogonal.
     *
     * @return int
     */
    public static function halftone4x4Orthogonal()
    {
        return self::HALFTONE_4X4_ORTHOGONAL;
    }

    /**
     * Dither type halftone 6x6 orthogonal.
     *
     * @return int
     */
    public static function halftone6x6Orthogonal()
    {
        return self::HALFTONE_6X6_ORTHOGONAL;
    }

    /**
     * Dither type halftone 8x8 orthogonal.
     *
     * @return int
     */
    public static function halftone8x8Orthogonal()
    {
        return self::HALFTONE_8X8_ORTHOGONAL;
    }

    /**
     * Dither type halftone 16x16 orthogonal.
     *
     * @return int
     */
    public static function halftone16x16Orthogonal()
    {
        return self::HALFTONE_16X16_ORTHOGONAL;
    }

    /**
     * Dither type circles 5x5 black.
     *
     * @return int
     */
    public static function circles5x5Black()
    {
        return self::CIRCLES_5X5_BLACK;
    }

    /**
     * Dither type circles 5x5 white.
     *
     * @return int
     */
    public static function circles5x5White()
    {
        return self::CIRCLES_5X5_WHITE;
    }

    /**
     * Dither type circles 6x6 black.
     *
     * @return int
     */
    public static function circles6x6Black()
    {
        return self::CIRCLES_6X6_BLACK;
    }

    /**
     * Dither type circles 6x6 white.
     *
     * @return int
     */
    public static function circles6x6White()
    {
        return self::CIRCLES_6X6_WHITE;
    }

    /**
     * Dither type circles 7x7 black.
     *
     * @return int
     */
    public static function circles7x7Black()
    {
        return self::CIRCLES_7X7_BLACK;
    }

    /**
     * Dither type circles 7x7 white.
     *
     * @return int
     */
    public static function circles7x7White()
    {
        return self::CIRCLES_7X7_WHITE;
    }
}
