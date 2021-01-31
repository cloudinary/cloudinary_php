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
 * Class TextAlignment
 */
class TextAlignment
{
    /**
     * Align text to the left.
     */
    const LEFT = 'left';

    /**
     * Align text to the center.
     */
    const CENTER = 'center';

    /**
     * Align text to the right.
     */
    const RIGHT = 'right';

    /**
     * Align text to the right in a left-to-right language.
     * Align text to the left in a right-to-left language.
     */
    const END = 'end';

    /**
     * Align text to the left in a left-to-right language.
     * Align text to the right in a right-to-left language.
     */
    const START = 'start';

    /**
     * Space out words such that the first word on the line is along the left edge and the last word is along the
     * right edge.
     */
    const JUSTIFY = 'justify';

    /**
     * Align text to the left.
     *
     * @return string
     */
    public static function left()
    {
        return self::LEFT;
    }

    /**
     * Align text to the center.
     *
     * @return string
     */
    public static function center()
    {
        return self::CENTER;
    }

    /**
     * Align text to the right.
     *
     * @return string
     */
    public static function right()
    {
        return self::RIGHT;
    }

    /**
     * Align text to the right in a left-to-right language.
     * Align text to the left in a right-to-left language.
     *
     * @return string
     */
    public static function end()
    {
        return self::END;
    }

    /**
     * Align text to the left in a left-to-right language.
     * Align text to the right in a right-to-left language.
     *
     * @return string
     */
    public static function start()
    {
        return self::START;
    }

    /**
     * Space out words such that the first word on the line is along the left edge and the last word is along the
     * right edge.
     *
     * @return string
     */
    public static function justify()
    {
        return self::JUSTIFY;
    }
}
