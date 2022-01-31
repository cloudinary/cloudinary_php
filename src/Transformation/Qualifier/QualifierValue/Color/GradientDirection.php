<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Argument;

/**
 * The available directions for a background gradient fade effect.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/image_transformations#content_aware_padding" target="_blank">
 * Content aware padding</a>
 *
 * @api
 */
class GradientDirection
{
    /**
     * Blend the colors horizontally.
     */
    const HORIZONTAL = 'horizontal';

    /**
     * Blend the colors vertically.
     */
    const VERTICAL = 'vertical';

    /**
     * Blend the colors diagonally from top-left to bottom-right.
     */
    const DIAGONAL_DESC = 'diagonal_desc';

    /**
     * Blend the colors diagonally from bottom-left to top-right.
     */
    const DIAGONAL_ASC = 'diagonal_asc';

    /**
     * Blend the colors horizontally.
     *
     * @return string
     */
    public static function horizontal()
    {
        return self::HORIZONTAL;
    }

    /**
     * Blend the colors vertically.
     *
     * @return string
     */
    public static function vertical()
    {
        return self::VERTICAL;
    }

    /**
     * Blend the colors diagonally from top-left to bottom-right.
     *
     * @return string
     */
    public static function diagonalDesc()
    {
        return self::DIAGONAL_DESC;
    }

    /**
     * Blend the colors diagonally from bottom-left to top-right.
     *
     * @return string
     */
    public static function diagonalAsc()
    {
        return self::DIAGONAL_ASC;
    }
}
