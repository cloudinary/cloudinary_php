<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Argument\Text;

use Cloudinary\Transformation\BorderQualifier;

/**
 * Class Stroke
 */
class Stroke
{
    /**
     * Do not include an outline stroke. (Server default)
     */
    const NONE = 'none';
    /**
     * Include an outline stroke.
     */
    const STROKE = 'stroke';

    /**
     * Do not include an outline stroke. (Server default)
     *
     * @return string
     */
    public static function none()
    {
        return self::NONE;
    }

    /**
     * Include an outline stroke.
     *
     * @return string
     */
    public static function stroke()
    {
        return self::STROKE;
    }

    /**
     * Include an outline stroke.
     *
     * @return BorderQualifier
     */
    public static function solid($width, $color)
    {
        return (new BorderQualifier())->style('solid')->width($width)->color($color);
    }
}
