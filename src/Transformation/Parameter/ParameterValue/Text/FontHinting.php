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
}
