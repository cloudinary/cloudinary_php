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

/**
 * Class FontStyle
 */
class FontStyle
{
    const NORMAL = 'normal';
    const ITALIC = 'italic';

    /**
     * Font style normal.
     *
     * @return string
     */
    public static function normal()
    {
        return self::NORMAL;
    }

    /**
     * Font style italic.
     *
     * @return string
     */
    public static function italic()
    {
        return self::ITALIC;
    }
}
