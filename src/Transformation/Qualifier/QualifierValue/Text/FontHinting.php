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
 * Class FontHinting
 */
class FontHinting
{
    /**
     * Do not hint outlines.
     */
    const NONE = 'none';

    /**
     * Hint outlines slightly to improve contrast while retaining good fidelity to the original shapes.
     */
    const SLIGHT = 'slight';

    /**
     * Hint outlines with medium strength, providing a compromise between fidelity to the original shapes and contrast.
     */
    const MEDIUM = 'medium';

    /**
     * Hint outlines to the maximize contrast.
     */
    const FULL = 'full';

    /**
     * Do not hint outlines.
     *
     * @return string
     */
    public static function none()
    {
        return self::NONE;
    }

    /**
     * Hint outlines slightly to improve contrast while retaining good fidelity to the original shapes.
     *
     * @return string
     */
    public static function slight()
    {
        return self::SLIGHT;
    }

    /**
     * Hint outlines with medium strength, providing a compromise between fidelity to the original shapes and contrast.
     *
     * @return string
     */
    public static function medium()
    {
        return self::MEDIUM;
    }

    /**
     * Hint outlines to the maximize contrast.
     *
     * @return string
     */
    public static function full()
    {
        return self::FULL;
    }
}
