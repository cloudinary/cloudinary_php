<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Tag;

use Cloudinary\Asset\Video;
use Cloudinary\Configuration\Configuration;

/**
 * Trait VideoTagDeliveryTypeTrait
 *
 * @api
 */
trait VideoTagDeliveryTypeTrait
{
    /**
     * Creates the video tag of the uploaded video.
     *
     * @param string|mixed                    $publicId      The public ID of the asset.
     * @param Configuration|string|array|null $configuration The Configuration source.
     *
     * @return static
     */
    public static function upload($publicId, $configuration = null)
    {
        return new static(Video::upload($publicId, $configuration));
    }

    /**
     * Creates the video tag of the fetched (remote) video URL.
     *
     * @param string $url The URL of the remote video.
     * @param Configuration|string|array|null $configuration The Configuration source.
     *
     * @return static
     */
    public static function fetch($url, $configuration = null)
    {
        return new static(Video::fetch($url, $configuration));
    }

    //TODO: populate the rest
}
