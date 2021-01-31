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
 * Class TextDecoration
 */
class TextDecoration
{
    const UNDERLINE     = 'underline';
    const STRIKETHROUGH = 'strikethrough';

    /**
     * Text decoration underline.
     *
     * @return string
     */
    public static function underline()
    {
        return self::UNDERLINE;
    }

    /**
     * Text decoration strikethrough.
     *
     * @return string
     */
    public static function strikethrough()
    {
        return self::STRIKETHROUGH;
    }
}
