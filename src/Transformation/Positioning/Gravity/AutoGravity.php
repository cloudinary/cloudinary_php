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
 * Automatically identifies the most interesting regions to include when resizing.
 *
 * **Learn more**:
 * <a href="https://cloudinary.com/documentation/image_transformations#automatic_cropping_g_auto" target="_blank">
 * Automatic gravity for images</a> |
 * <a href="https://cloudinary.com/documentation/video_manipulation_and_delivery#automatic_cropping" target="_blank">
 * Automatic gravity for videos</a>
 *
 * @api
 */
abstract class AutoGravity
{
    const CLASSIC = 'classic';
    const SUBJECT = 'subject';

    /**
     * @return string
     */
    public static function classic()
    {
        return self::CLASSIC;
    }

    /**
     * @return string
     */
    public static function subject()
    {
        return self::SUBJECT;
    }

    /**
     * @param mixed $gravity The gravity to use.
     *
     * @return string
     */
    public static function object($gravity)
    {
        return new AutoGravityObject($gravity);
    }
}
