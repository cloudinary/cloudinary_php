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
 * Class FontWeight
 */
class FontWeight
{
    const THIN        = 'thin';
    const EXTRA_LIGHT = 'extralight';
    const LIGHT       = 'light';
    const NORMAL      = 'normal';
    const BOOK        = 'book';
    const MEDIUM      = 'medium';
    const DEMIBOLD    = 'demibold';
    const SEMIBOLD    = 'semibold';
    const BOLD        = 'bold';
    const EXTRABOLD   = 'extrabold';
    const ULTRABOLD   = 'ultrabold';
    const BLACK       = 'black';
    const HEAVY       = 'heavy';

    /**
     * Font weight thin.
     *
     * @return string
     */
    public static function thin()
    {
        return self::THIN;
    }

    /**
     * Font weight extraLight.
     *
     * @return string
     */
    public static function extraLight()
    {
        return self::EXTRA_LIGHT;
    }

    /**
     * Font weight light.
     *
     * @return string
     */
    public static function light()
    {
        return self::LIGHT;
    }

    /**
     * Font weight normal.
     *
     * @return string
     */
    public static function normal()
    {
        return self::NORMAL;
    }

    /**
     * Font weight book.
     *
     * @return string
     */
    public static function book()
    {
        return self::BOOK;
    }

    /**
     * Font weight medium.
     *
     * @return string
     */
    public static function medium()
    {
        return self::MEDIUM;
    }

    /**
     * Font weight demibold.
     *
     * @return string
     */
    public static function demibold()
    {
        return self::DEMIBOLD;
    }

    /**
     * Font weight semibold.
     *
     * @return string
     */
    public static function semibold()
    {
        return self::SEMIBOLD;
    }

    /**
     * Font weight bold.
     *
     * @return string
     */
    public static function bold()
    {
        return self::BOLD;
    }

    /**
     * Font weight extrabold.
     *
     * @return string
     */
    public static function extrabold()
    {
        return self::EXTRABOLD;
    }

    /**
     * Font weight ultrabold.
     *
     * @return string
     */
    public static function ultrabold()
    {
        return self::ULTRABOLD;
    }

    /**
     * Font weight black.
     *
     * @return string
     */
    public static function black()
    {
        return self::BLACK;
    }

    /**
     * Font weight heavy.
     *
     * @return string
     */
    public static function heavy()
    {
        return self::HEAVY;
    }
}
