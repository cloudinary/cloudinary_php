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
 *
 * Generates an HTML `<img>` tag based on a captured frame from the specified video source.
 *
 * @api
 */
class VideoThumbnailTag extends ImageTag
{
    /**
     * Sets the image of the tag.
     *
     * @param string|Video                    $publicId      The public ID of the video.
     * @param Configuration|string|array|null $configuration The Configuration source.
     *
     * @return static
     */
    public function image($publicId, $configuration = null)
    {
        parent::image(new Video($publicId, $configuration), $configuration);

        $this->image->asset->extension = $configuration->tag->videoPosterFormat;

        return $this;
    }
}
