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

use Cloudinary\Asset\AssetType;
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
 * @internal
 */
abstract class Tag
{
    /**
     * Creates a new image tag.
     *
     * @param string|Image             $image                    The Public ID or the Image instance.
     * @param Configuration|null       $configuration            The configuration instance.
     * @param ImageTransformation|null $additionalTransformation The additional transformation.
     *
     */
    public static function imageTag(
        string|Image $image,
        ?Configuration $configuration = null,
        ?ImageTransformation $additionalTransformation = null
    ): ImageTag {
        return new ImageTag($image, $configuration, $additionalTransformation);
    }

    /**
     * Creates a new video tag.
     *
     * @param string|Video       $video         The public ID or Video instance.
     * @param array|null         $sources       The tag sources definition.
     * @param Configuration|null $configuration The configuration instance.
     *
     */
    public static function videoTag(
        Video|string $video,
        ?array $sources = null,
        ?Configuration $configuration = null
    ): VideoTag {
        return new VideoTag($video, $sources, $configuration);
    }

    /**
     * Generates an HTML `<img>` tag based on a captured frame from the specified video source.
     *
     * @param string|Video       $video         The public ID of the video.
     * @param Configuration|null $configuration The configuration instance.
     *
     *
     * @see VideoThumbnailTag
     */
    public static function videoThumbnailTag(
        Video|string $video,
        ?Configuration $configuration = null
    ): VideoThumbnailTag {
        return new VideoThumbnailTag($video, $configuration);
    }

    /**
     * Generates an HTML `<picture>` tag containing `<source>` and `<img>` tags.
     *
     * @param string|Image       $image         The public ID or Image instance.
     * @param array              $sources       The sources definitions.
     * @param Configuration|null $configuration The configuration instance.
     *
     *
     *
     * @see PictureTag
     */
    public static function pictureTag(
        string|Image $image,
        array $sources,
        ?Configuration $configuration = null
    ): PictureTag {
        return new PictureTag($image, $sources, $configuration);
    }

    /**
     * Generates an HTML `<script/>` tag for JavaScript.
     *
     * @param Configuration|null $configuration The configuration instance.
     *
     *
     * @see JsConfigTag
     */
    public static function jsConfigTag(?Configuration $configuration = null): JsConfigTag
    {
        return new JsConfigTag($configuration);
    }

    /**
     * Generates an HTML `<link>` tag to specify the relationship to the CSS file associated with an image sprite.
     *
     * @param string                   $tag                      The sprite is created from all images with this tag.
     * @param Configuration|null       $configuration            The configuration instance.
     * @param ImageTransformation|null $additionalTransformation The additional transformation.
     *
     *
     * @see SpriteTag
     */
    public static function spriteTag(
        string $tag,
        ?Configuration $configuration = null,
        ?ImageTransformation $additionalTransformation = null
    ): SpriteTag {
        return new SpriteTag($tag, $configuration, $additionalTransformation);
    }

    /**
     * Generates an HTML `<meta>` tag to indicate support for Client Hints.
     *
     * @param Configuration|null $configuration The configuration instance.
     *
     *
     * @see ClientHintsMetaTag
     */
    public static function clientHintsMetaTag(?Configuration $configuration = null): ClientHintsMetaTag
    {
        return new ClientHintsMetaTag($configuration);
    }

    /**
     * Generates an HTML `<form>` tag.
     *
     * @param Configuration|null $configuration The configuration instance.
     * @param array              $uploadParams  The upload parameters.
     * @param string             $assetType     The type of the asset.
     *
     *
     * @see FormTag
     */
    public static function formTag(
        ?Configuration $configuration = null,
        array $uploadParams = [],
        string $assetType = AssetType::AUTO
    ): FormTag {
        return new FormTag($configuration, $uploadParams, $assetType);
    }

    /**
     * Generates an HTML `<input>` tag to use for uploading files.
     *
     * @param string             $field         The name of an input field in the same form that will be updated
     *                                          post-upload with the asset's metadata.
     * @param Configuration|null $configuration The configuration instance.
     * @param array              $uploadParams  The upload parameters.
     * @param string             $assetType     The type of the asset.
     *
     *
     * @see UploadTag
     */
    public static function uploadTag(
        string $field,
        ?Configuration $configuration = null,
        array $uploadParams = [],
        string $assetType = AssetType::AUTO
    ): UploadTag {
        return new UploadTag($field, $configuration, $uploadParams, $assetType);
    }
}
