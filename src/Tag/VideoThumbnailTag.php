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

use Cloudinary\ArrayUtils;
use Cloudinary\Asset\AssetType;
use Cloudinary\Asset\Video;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Configuration\TagConfig;

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
     * @param string|Video                    $source        The public ID of the video.
     * @param Configuration|string|array|null $configuration The Configuration source.
     *
     * @return static
     */
    public function image($source, $configuration = null)
    {
        parent::image(new Video($source, $configuration), $configuration);

        $this->image->setFormat($configuration->tag->videoPosterFormat);

        return $this;
    }

    /**
     * Creates a video poster image tag for a video from the provided source and an array of parameters.
     *
     * @param string $source The public ID of the asset.
     * @param array  $params The asset parameters.
     *
     * @return VideoThumbnailTag
     */
    public static function fromParams($source, $params = [])
    {
        $configuration = self::fromParamsDefaultConfig();

        ArrayUtils::setDefaultValue($params, 'resource_type', AssetType::VIDEO);
        ArrayUtils::setDefaultValue($params, 'format', $configuration->tag->videoPosterFormat);

        return parent::fromParams($source, $params);
    }
}
