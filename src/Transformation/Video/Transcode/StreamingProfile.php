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

use Cloudinary\Transformation\Parameter\BaseParameter;

/**
 * The predefined streaming profiles.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/video_manipulation_and_delivery#predefined_streaming_profiles"
 * target="_blank">Predefined streaming profiles</a>
 *
 * @api
 */
class StreamingProfile extends BaseParameter
{
    const SP_4K        = '4k';
    const FULL_HD      = 'full_hd';
    const HD           = 'hd';
    const SD           = 'sd';
    const FULL_HD_WIFI = 'full_hd_wifi';
    const FULL_HD_LEAN = 'full_hd_lean';
    const HD_LEAN      = 'hd_lean';
}
