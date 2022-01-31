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
    protected $configuration;

    /**
     * Tag constructor.
     *
     * @param Configuration|string|array|null $configuration The Configuration source.
     */
    public function __construct($configuration = null)
    {
        $this->configuration = $configuration;
    }

    /**
     * Creates a new image tag.
     *
     * @param string|Image        $image                    The Public ID or the Image instance.
     * @param ImageTransformation $additionalTransformation The additional transformation.
     *
     * @return ImageTag
     */
    public function imageTag($image, $additionalTransformation = null)
    {
        return new ImageTag($image, $this->configuration, $additionalTransformation);
    }

    /**
     * Creates a new video tag.
     *
     * @param string|Video $video   The public ID or Video instance.
     * @param array|null   $sources The tag sources definition.
     *
     * @return VideoTag
     */
    public function videoTag($video, $sources = null)
    {
        return new VideoTag($video, $sources, $this->configuration);
    }

    /**
     * Generates an HTML `<img>` tag based on a captured frame from the specified video source.
     *
     * @param string|Video $video The public ID of the video.
     *
     * @return VideoThumbnailTag
     *
     * @see VideoThumbnailTag
     */
    public function videoThumbnailTag($video)
    {
        return new VideoThumbnailTag($video, $this->configuration);
    }

    /**
     * Generates an HTML `<picture>` tag containing `<source>` and `<img>` tags.
     *
     * @param string|Image  $image         The public ID or Image instance.
     * @param array         $sources       The sources definitions.
     *
     * @return PictureTag
     *
     * @see PictureTag
     */
    public function pictureTag($image, $sources)
    {
        return new PictureTag($image, $sources, $this->configuration);
    }

    /**
     * Generates an HTML `<script/>` tag for JavaScript.
     *
     * @return JsConfigTag
     *
     * @see JsConfigTag
     */
    public function jsConfigTag()
    {
        return new JsConfigTag($this->configuration);
    }

    /**
     * Generates an HTML `<link>` tag to specify the relationship to the CSS file associated with an image sprite.
     *
     * @param string              $tag                      The sprite is created from all images with this tag.
     * @param ImageTransformation $additionalTransformation The additional transformation.
     *
     * @return SpriteTag
     *
     * @see SpriteTag
     */
    public function spriteTag($tag, $additionalTransformation = null)
    {
        return new SpriteTag($tag, $this->configuration, $additionalTransformation);
    }

    /**
     * Generates an HTML `<meta>` tag to indicate support for Client Hints.
     *
     * @return ClientHintsMetaTag
     *
     * @see ClientHintsMetaTag
     */
    public function clientHintsMetaTag()
    {
        return new ClientHintsMetaTag($this->configuration);
    }

    /**
     * Generates an HTML `<form>` tag.
     *
     * @param array  $uploadParams The upload parameters.
     * @param string $assetType    The type of the asset.
     *
     * @return FormTag
     *
     * @see FormTag
     */
    public function formTag($uploadParams = [], $assetType = AssetType::AUTO)
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
     * @return UploadTag
     *
     * @see UploadTag
     */
    public function uploadTag($field, $uploadParams = [], $assetType = AssetType::AUTO)
    {
        return new UploadTag($field, $this->configuration, $uploadParams, $assetType);
    }
}
