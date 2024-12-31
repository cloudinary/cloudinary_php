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
use Cloudinary\ClassUtils;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Transformation\CommonTransformation;
use Cloudinary\Transformation\VideoTransformation;

/**
 *
 * Generates an HTML `<source>` tag that can be used with a `<video>` tag.
 *
 * For example:
 *
 * ```
 * <video poster="https://res.cloudinary.com/demo/video/upload/dog.jpg">
 * <source src="https://res.cloudinary.com/demo/video/upload/vc_h265/dog.mp4" type="video/mp4; codecs=hev1">
 * <source src="https://res.cloudinary.com/demo/video/upload/vc_vp9/dog.webm" type="video/webm; codecs=vp9">
 * <source src="https://res.cloudinary.com/demo/video/upload/vc_auto/dog.mp4" type="video/mp4">
 * <source src="https://res.cloudinary.com/demo/video/upload/vc_auto/dog.webm" type="video/webm">
 * </video>
 * ```
 *
 * @api
 */
class VideoSourceTag extends BaseTag
{
    public const    NAME    = 'source';
    protected const IS_VOID = true;

    /**
     * @var Video $video The video source of the tag.
     */
    public Video $video;

    /**
     * @var SourceType $sourceType The 'type' attribute.
     */
    protected SourceType $sourceType;

    /**
     * @var mixed $additionalTransformation Additional transformation to be applied on the tag video.
     */
    public mixed $additionalTransformation;

    /**
     * VideoSourceTag constructor.
     *
     * @param mixed                           $asset                    The public ID or the Video asset.
     * @param array|string|Configuration|null $configuration            The Configuration source.
     * @param mixed                           $additionalTransformation Additional transformation to be applied.
     */
    public function __construct(
        $asset,
        Configuration|array|string|null $configuration = null,
        mixed $additionalTransformation = null
    ) {
        parent::__construct($configuration);

        $this->video($asset, $configuration);

        $this->additionalTransformation = $additionalTransformation;
    }

    /**
     * Sets the video of the tag.
     *
     * @param mixed              $video         The public ID or the Video asset.
     * @param Configuration|null $configuration The configuration instance.
     *
     */
    public function video(mixed $video, ?Configuration $configuration = null): static
    {
        $this->video = new Video($video, $configuration);

        return $this;
    }

    /**
     * Sets the type.
     *
     * @param string|SourceType $type   The type of the source.
     * @param array|string|null $codecs The codecs.
     *
     */
    public function type(SourceType|string $type, array|string|null $codecs = null): static
    {
        $this->sourceType = ClassUtils::verifyInstance($type, VideoSourceType::class, null, $codecs);

        return $this;
    }

    /**
     * Serializes the tag attributes.
     *
     * @param array $attributes Optional. Additional attributes to add without affecting the tag state.
     *
     */
    public function serializeAttributes(array $attributes = []): string
    {
        if (! empty((string)$this->video)) {
            $toSerialize = new Video($this->video);
            $toSerialize->setFormat($this->sourceType->type, $this->config->tag->useFetchFormat);
            $attributes['src'] = $toSerialize->toUrl($this->additionalTransformation);
        }

        if (! empty((string)$this->sourceType)) {
            $attributes['type'] = $this->sourceType;
        }

        return parent::serializeAttributes($attributes);
    }
}
