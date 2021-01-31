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
 * Defines how to transcode a video to another format
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/video_manipulation_and_delivery#transcoding_video_to_other_formats"
 * target="_blank">Transcoding video to other formats</a>
 *
 * @api
 */
abstract class Transcode
{
    use AudioQualifierTrait;
    use VideoQualifierTrait;
    use TranscodeTrait;
}
