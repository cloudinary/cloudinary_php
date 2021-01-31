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
 * Class Effect
 */
abstract class ColorEffect
{
    // Image effects
    const BLACKWHITE     = 'blackwhite';
    const COLORIZE       = 'colorize';
    const GRAYSCALE      = 'grayscale';
    const NEGATE         = 'negate';
    const SEPIA          = 'sepia';
    // Accessibility
    const ASSIST_COLOR_BLIND   = 'assist_colorblind';
    const SIMULATE_COLOR_BLIND = 'simulate_colorblind';

    use ImageColorEffectTrait;
}
