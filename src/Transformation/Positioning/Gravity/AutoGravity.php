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
 * Class AutoGravity
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
