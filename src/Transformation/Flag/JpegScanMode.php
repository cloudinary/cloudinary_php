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
 * Class JpegScanMode
 */
class JpegScanMode
{
    /**
     * A smart optimization of the decoding time, compression level and progressive rendering (less iterations).
     * This is the default mode when using q_auto.
     */
    const SEMI  = 'semi';

    /**
     *  Delivers a preview very quickly, and in a single later phase improves the image to the required resolution.
     */
    const STEEP = 'steep';

    /**
     *  Use this to deliver a non-progressive image. This is the default mode when setting a specific value for quality.
     */
    const NONE  = 'none';
}
