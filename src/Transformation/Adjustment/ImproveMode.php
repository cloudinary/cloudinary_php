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
 * Class ImproveMode
 *
 * Used by Improve adjustment.
 *
 * @package Cloudinary\Transformation
 */
class ImproveMode
{
    /**
     * INDOOR mode. Use this mode to get better results on images with indoor lighting and shadows.
     */
    const INDOOR = 'indoor';

    /**
     * OUTDOOR mode (Server default).
     */
    const OUTDOOR = 'outdoor';

    /**
     * INDOOR mode. Use this mode to get better results on images with indoor lighting and shadows.
     *
     * @return string
     */
    public static function indoor()
    {
        return self::INDOOR;
    }

    /**
     * OUTDOOR mode (Server default).
     *
     * @return string
     */
    public static function outdoor()
    {
        return self::OUTDOOR;
    }
}
