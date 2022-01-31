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
 * Adjusts the visual appearance of an image or video.
 *
 * @api
 */
abstract class Adjust
{
    // Common adjustments
    const BRIGHTNESS = 'brightness';
    const CONTRAST   = 'contrast';
    const SATURATION = 'saturation';
    const GAMMA      = 'gamma';

    // Image adjustments
    const RED            = 'red';
    const GREEN          = 'green';
    const BLUE           = 'blue';
    const BRIGHTNESS_HSB = 'brightness_hsb';
    const HUE            = 'hue';
    const TINT           = 'tint';
    const REPLACE_COLOR  = 'replace_color';
    const RECOLOR        = 'recolor';

    const AUTO_BRIGHTNESS = 'auto_brightness';
    const AUTO_COLOR      = 'auto_color';
    const AUTO_CONTRAST   = 'auto_contrast';
    const FILL_LIGHT      = 'fill_light';
    const IMPROVE         = 'improve';
    const VIBRANCE        = 'vibrance';
    const VIESUS_CORRECT  = 'viesus_correct';

    const SHARPEN          = 'sharpen';
    const UNSHARP_MASK     = 'unsharp_mask';

    const OPACITY_THRESHOLD = 'opacity_threshold';

    use CommonAdjustmentTrait;
    use ImageAdjustmentTrait;
}
