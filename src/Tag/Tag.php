<?php

namespace Cloudinary\Tag;

use Cloudinary\Asset\Image;
use Cloudinary\Asset\Video;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Transformation\ImageTransformation;

/**
 * Static builder for all HTML tags.
 *
 * Example usage:
 *
 * ```
 * Tag::imageTag("sample.jpg");
 * ```
 *
 * @api
 */
abstract class Tag
{
    /**
     * Creates a new image tag.
     *
     * @param string|Image                    $image                    The Public ID or Image instance
     * @param Configuration|string|array|null $configuration            The Configuration source.
     * @param ImageTransformation             $additionalTransformation The additional transformation.
     *
     * @return ImageTag
     */
    public static function imageTag($image, $configuration = null, $additionalTransformation = null)
    {
        return new ImageTag($image, $configuration, $additionalTransformation);
    }

    /**
     * Creates a new video tag.
     *
     * @param string|Video  $video         The public ID or Video instance.
     * @param array|null    $sources       The tag sources definition.
     * @param Configuration $configuration The configuration instance.
     *
     * @return VideoTag
     */
    public static function videoTag($video, $sources = null, $configuration = null)
    {
        return new VideoTag($video, $sources, $configuration);
    }

    /**
     * @return ClientHintsMetaTag
     */
    public static function clientHintsMetaTag()
    {
        return new ClientHintsMetaTag();
    }
}
