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
     * @param string|Image        $image                    The Public ID or the Image instance.
     * @param Configuration       $configuration            The configuration instance.
     * @param ImageTransformation $additionalTransformation The additional transformation.
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
     * Generates an HTML `<img>` tag based on a captured frame from the specified video source.
     *
     * @param string|Video  $video         The public ID of the video.
     * @param Configuration $configuration The configuration instance.
     *
     * @return VideoThumbnailTag
     *
     * @see VideoThumbnailTag
     */
    public static function videoThumbnailTag($video, $configuration = null)
    {
        return new VideoThumbnailTag($video, $configuration);
    }

    /**
     * Generates an HTML `<picture>` tag containing `<source>` and `<img>` tags.
     *
     * @param string|Image  $image         The public ID or Image instance.
     * @param array         $sources       The sources definitions.
     * @param Configuration $configuration The configuration instance.
     *
     *
     * @return PictureTag
     *
     * @see PictureTag
     */
    public static function pictureTag($image, $sources, $configuration = null)
    {
        return new PictureTag($image, $sources, $configuration);
    }

    /**
     * Generates an HTML `<script/>` tag for JavaScript.
     *
     * @param Configuration $configuration The configuration instance.
     *
     * @return JsConfigTag
     *
     * @see JsConfigTag
     */
    public static function jsConfigTag($configuration = null)
    {
        return new JsConfigTag($configuration);
    }

    /**
     * Generates an HTML `<link>` tag to specify the relationship to the CSS file associated with an image sprite.
     *
     * @param string              $tag                      The sprite is created from all images with this tag.
     * @param Configuration       $configuration            The configuration instance.
     * @param ImageTransformation $additionalTransformation The additional transformation.
     *
     * @return SpriteTag
     *
     * @see SpriteTag
     */
    public static function spriteTag($tag, $configuration = null, $additionalTransformation = null)
    {
        return new SpriteTag($tag, $configuration, $additionalTransformation);
    }

    /**
     * Generates an HTML `<meta>` tag to indicate support for Client Hints.
     *
     * @param Configuration $configuration The configuration instance.
     *
     * @return ClientHintsMetaTag
     *
     * @see ClientHintsMetaTag
     */
    public static function clientHintsMetaTag($configuration = null)
    {
        return new ClientHintsMetaTag($configuration);
    }

    /**
     * Generates an HTML `<form>` tag.
     *
     * @param Configuration $configuration The configuration instance.
     * @param array         $uploadParams  The upload parameters.
     * @param string        $assetType     The type of the asset.
     *
     * @return FormTag
     *
     * @see FormTag
     */
    public static function formTag($configuration = null, $uploadParams = [], $assetType = AssetType::AUTO)
    {
        return new FormTag($configuration, $uploadParams, $assetType);
    }

    /**
     * Generates an HTML `<input>` tag to use for uploading files.
     *
     * @param string        $field         The name of an input field in the same form that will be updated post-upload
     *                                     with the asset's metadata.
     * @param Configuration $configuration The configuration instance.
     * @param array         $uploadParams  The upload parameters.
     * @param string        $assetType     The type of the asset.
     *
     * @return UploadTag
     *
     * @see UploadTag
     */
    public static function uploadTag($field, $configuration = null, $uploadParams = [], $assetType = AssetType::AUTO)
    {
        return new UploadTag($field, $configuration, $uploadParams, $assetType);
    }
}
