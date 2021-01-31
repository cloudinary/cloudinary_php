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
    const NAME    = 'source';
    const IS_VOID = true;

    /**
     * @var Video $video The video source of the tag.
     */
    public $video;

    /**
     * @var SourceType $sourceType The 'type' attribute.
     */
    protected $sourceType;

    /**
     * @var VideoTransformation $additionalTransformation Additional transformation to be applied on the tag video.
     */
    public $additionalTransformation;

    /**
     * VideoSourceTag constructor.
     *
     * @param mixed                           $asset                    The public ID or the Video asset.
     * @param Configuration|string|array|null $configuration            The Configuration source.
     * @param null                            $additionalTransformation Additional transformation to be applied.
     */
    public function __construct($asset, $configuration = null, $additionalTransformation = null)
    {
        parent::__construct($configuration);

        $this->video($asset, $configuration);

        $this->additionalTransformation = $additionalTransformation;
    }

    /**
     * Sets the video of the tag.
     *
     * @param mixed         $video         The public ID or the Video asset.
     * @param Configuration $configuration The configuration instance.
     *
     * @return static
     */
    public function video($video, $configuration = null)
    {
        $this->video = new Video($video, $configuration);

        return $this;
    }

    /**
     * Sets the type.
     *
     * @param SourceType|string $type   The type of the source.
     * @param string|array      $codecs The codecs.
     *
     * @return static
     */
    public function type($type, $codecs = null)
    {
        $this->sourceType = ClassUtils::verifyInstance($type, VideoSourceType::class, null, $codecs);

        $this->video->asset->extension = $this->sourceType->type;

        return $this;
    }

    /**
     * Serializes the tag attributes.
     *
     * @param array $attributes Optional. Additional attributes to add without affecting the tag state.
     *
     * @return string
     */
    public function serializeAttributes($attributes = [])
    {
        if (! empty((string)$this->video)) {
            $attributes['src'] = $this->video->toUrl($this->additionalTransformation);
        }

        if (! empty((string)$this->sourceType)) {
            $attributes['type'] = $this->sourceType;
        }

        return parent::serializeAttributes($attributes);
    }
}
