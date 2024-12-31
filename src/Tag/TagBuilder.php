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
 * Builder for all HTML tags.
 *
 * Example usage:
 *
 * ```
 * $cloudinary->tag()->imageTag("sample.jpg");
 * ```
 *
 * @internal
 */
class TagBuilder
{
    protected string|array|null|Configuration $configuration;

    /**
     * Tag constructor.
     *
     * @param array|string|Configuration|null $configuration The Configuration source.
     */
    public function __construct(Configuration|array|string|null $configuration = null)
    {
        $this->configuration = $configuration;
    }

    /**
     * Creates a new image tag.
     *
     * @param string|Image             $image                    The Public ID or the Image instance.
     * @param ImageTransformation|null $additionalTransformation The additional transformation.
     *
     */
    public function imageTag(string|Image $image, ?ImageTransformation $additionalTransformation = null): ImageTag
    {
        return new ImageTag($image, $this->configuration, $additionalTransformation);
    }

    /**
     * Creates a new video tag.
     *
     * @param string|Video $video   The public ID or Video instance.
     * @param array|null   $sources The tag sources definition.
     *
     */
    public function videoTag(Video|string $video, ?array $sources = null): VideoTag
    {
        return new VideoTag($video, $sources, $this->configuration);
    }

    /**
     * Generates an HTML `<img>` tag based on a captured frame from the specified video source.
     *
     * @param string|Video $video The public ID of the video.
     *
     *
     * @see VideoThumbnailTag
     */
    public function videoThumbnailTag(Video|string $video): VideoThumbnailTag
    {
        return new VideoThumbnailTag($video, $this->configuration);
    }

    /**
     * Generates an HTML `<picture>` tag containing `<source>` and `<img>` tags.
     *
     * @param string|Image $image   The public ID or Image instance.
     * @param array        $sources The sources definitions.
     *
     *
     * @see PictureTag
     */
    public function pictureTag(string|Image $image, array $sources): PictureTag
    {
        return new PictureTag($image, $sources, $this->configuration);
    }

    /**
     * Generates an HTML `<script/>` tag for JavaScript.
     *
     *
     * @see JsConfigTag
     */
    public function jsConfigTag(): JsConfigTag
    {
        return new JsConfigTag($this->configuration);
    }

    /**
     * Generates an HTML `<link>` tag to specify the relationship to the CSS file associated with an image sprite.
     *
     * @param string                   $tag                      The sprite is created from all images with this tag.
     * @param ImageTransformation|null $additionalTransformation The additional transformation.
     *
     *
     * @see SpriteTag
     */
    public function spriteTag(string $tag, ?ImageTransformation $additionalTransformation = null): SpriteTag
    {
        return new SpriteTag($tag, $this->configuration, $additionalTransformation);
    }

    /**
     * Generates an HTML `<meta>` tag to indicate support for Client Hints.
     *
     *
     * @see ClientHintsMetaTag
     */
    public function clientHintsMetaTag(): ClientHintsMetaTag
    {
        return new ClientHintsMetaTag($this->configuration);
    }

    /**
     * Generates an HTML `<form>` tag.
     *
     * @param array  $uploadParams The upload parameters.
     * @param string $assetType    The type of the asset.
     *
     *
     * @see FormTag
     */
    public function formTag(array $uploadParams = [], string $assetType = AssetType::AUTO): FormTag
    {
        return new FormTag($this->configuration, $uploadParams, $assetType);
    }

    /**
     * Generates an HTML `<input>` tag to use for uploading files.
     *
     * @param string $field                The name of an input field in the same form that will be updated post-upload
     *                                     with the asset's metadata.
     * @param array  $uploadParams         The upload parameters.
     * @param string $assetType            The type of the asset.
     *
     *
     * @see UploadTag
     */
    public function uploadTag(string $field, array $uploadParams = [], string $assetType = AssetType::AUTO): UploadTag
    {
        return new UploadTag($field, $this->configuration, $uploadParams, $assetType);
    }
}
