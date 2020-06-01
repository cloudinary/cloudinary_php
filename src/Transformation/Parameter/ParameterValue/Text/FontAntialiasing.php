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
 * Class FontAntialiasing
 */
class FontAntialiasing
{
    /**
     * Use a bi-level alpha mask
     */
    const NONE     = 'none';

    /**
     * Perform single-color antialiasing. For example, using shades of gray for black text on a white background.
     */
    const GRAY     = 'gray';

    /**
     * Perform antialiasing by taking advantage of the order of subpixel elements on devices such as LCD panels.
     */
    const SUBPIXEL = 'subpixel';

    /**
     * Some antialiasing is performed, but speed is prioritized over quality.
     */
    const FAST     = 'fast';

    /**
     * Antialiasing that balances quality and performance.
     */
    const GOOD     = 'good';

    /**
     * Renders at the highest quality, sacrificing speed if necessary.
     */
    const BEST     = 'best';
}
