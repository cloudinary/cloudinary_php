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
 * Interface VideoFlagInterface
 */
interface VideoFlagInterface
{
    const STREAMING_ATTACHMENT = 'streaming_attachment';
    const HLSV3                = 'hlsv3';
    const KEEP_DAR             = 'keep_dar';
    const NO_STREAM            = 'no_stream';
    const MONO                 = 'mono';
    const TRUNCATE_TS          = 'truncate_ts';
    const WAVEFORM             = 'waveform';
}
