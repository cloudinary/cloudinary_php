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
 * Class EffectRange
 *
 * @internal
 */
class EffectRange
{
    const PERCENT          = [0, 100];
    const POSITIVE_PERCENT = [1, 100];
    const EXTENDED_PERCENT = [-50, 100];
    const AUDIO_VOLUME     = [-100, 400];
    const ANGLE            = [-360, 360];
    const PIXEL            = [1, 2000];
    const PIXEL_REGION     = [1, 200];
    const DEFAULT_RANGE    = [-100, 100];
    const SHIFTED_RANGE    = [-50, 150];
    const BRIGHTNESS       = [-99, 100];
    const DESHAKE          = [0, 64]; //FIXME: set predefined values
    const ORDERED_DITHER   = [0, 18];
}
