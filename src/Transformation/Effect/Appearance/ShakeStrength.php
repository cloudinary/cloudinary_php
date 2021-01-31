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
 * Class ShakeStrength
 */
class ShakeStrength
{
    const PIXELS_16 = 16;
    const PIXELS_32 = 32;
    const PIXELS_48 = 48;
    const PIXELS_64 = 64;

    /**
     * Shake strength 16.
     *
     * @return int
     */
    public static function pixels16()
    {
        return self::PIXELS_16;
    }
    /**
     * Shake strength 32.
     *
     * @return int
     */
    public static function pixels32()
    {
        return self::PIXELS_32;
    }
    /**
     * Shake strength 48.
     *
     * @return int
     */
    public static function pixels48()
    {
        return self::PIXELS_48;
    }
    /**
     * Shake strength 64.
     *
     * @return int
     */
    public static function pixels64()
    {
        return self::PIXELS_64;
    }
}
