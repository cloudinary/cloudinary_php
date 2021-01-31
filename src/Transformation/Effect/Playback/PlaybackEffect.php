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
abstract class PlaybackEffect
{
    const ACCELERATE = 'accelerate';
    const LOOP       = 'loop';
    const BOOMERANG  = 'boomerang';
    const PREVIEW    = 'preview';
    const REVERSE    = 'reverse';
    const VOLUME     = 'volume';
    const TRANSITION = 'transition';

    use PlaybackEffectTrait;
}
