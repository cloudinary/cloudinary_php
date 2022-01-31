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
 * Interface CommonFlagInterface
  *
 * @internal
 */
interface CommonFlagInterface
{
    const ATTACHMENT             = 'attachment';
    const IGNORE_ASPECT_RATIO    = 'ignore_aspect_ratio';
    const FORCE_ICC              = 'force_icc';
    const FORCE_STRIP            = 'force_strip';
    const GET_INFO               = 'getinfo';
    const IMMUTABLE_CACHE        = 'immutable_cache';
    const KEEP_ATTRIBUTION       = 'keep_attribution';
    const KEEP_IPTC              = 'keep_iptc';
}
