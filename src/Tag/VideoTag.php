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
use Cloudinary\Asset\AssetDescriptorTrait;
use Cloudinary\Asset\Image;
use Cloudinary\Asset\Video;
use Cloudinary\Configuration\AssetConfigTrait;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Transformation\BaseAction;
use Cloudinary\Transformation\CommonTransformation;
use Cloudinary\Transformation\ImageTransformation;
use Cloudinary\Transformation\Parameter\BaseParameter;
use Cloudinary\Transformation\VideoCodec;
use Cloudinary\Transformation\VideoTransformationInterface;
use Cloudinary\Transformation\VideoTransformationTrait;

/**
 * Generates an HTML `<video>` tag with specified attributes, containing `<source>` tags for the transformation URLs.
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
class VideoTag extends BaseTag implements VideoTransformationInterface
{
    use VideoTagDeliveryTypeTrait;
    use VideoTransformationTrait;
    use AssetDescriptorTrait;
    use AssetConfigTrait;

    const NAME = 'video';

    /**
     * @var Video $video The video of the tag.
     */
    protected $video;

    /**
     * @var array $sources VideoSourceTag array
     */
    protected $sources;

    /**
     * The default video sources of the video tag.
     *
     * @return array
     */
    public static function defaultVideoSources()
    {
        return [
            [
                'type'           => VideoSourceType::mp4('hev1'),
                'transformation' => VideoCodec::h265(),
            ],
            [
                'type'           => VideoSourceType::webm('vp9'),
                'transformation' => VideoCodec::vp9(),
            ],
            [
                'type'           => VideoSourceType::mp4(),
                'transformation' => VideoCodec::auto(),
            ],
            [
                'type'           => VideoSourceType::webm(),
                'transformation' => VideoCodec::auto(),
            ],
        ];
    }

    protected static $defaultSourceTypes = ['webm', 'mp4', 'ogv'];

    /**
     * VideoTag constructor.
     *
     * @param string|Video  $video         The public ID or Video instance.
     * @param array|null    $sources       The tag sources definition.
     * @param Configuration $configuration The configuration instance.
     */
    public function __construct($video, $sources = null, $configuration = null)
    {
        parent::__construct($configuration);

        $this->video($video, $configuration);

        $sources = $sources !== null ? $sources : self::defaultVideoSources();
        $this->sources($sources);
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
     * Sets the tag sources.
     *
     * @param array $sourcesDefinitions The definitions of the sources.
     *
     * @return $this
     */
    public function sources($sourcesDefinitions)
    {
        $this->sources = [];

        foreach ($sourcesDefinitions as $source) {
            if (is_array($source)) {
                $sourceTag = new VideoSourceTag(
                    $this->video,
                    $this->config,
                    ArrayUtils::get($source, 'transformation')
                );
                $sourceTag->type(ArrayUtils::get($source, 'type'));

                $source = $sourceTag;
            }

            $this->sources [] = $source;
        }

        return $this;
    }

    /**
     * Creates a new video tag from the provided source and an array of parameters.
     *
     * @param string $source The public ID of the asset.
     * @param array  $params The asset parameters.
     *
     * @return mixed
     */

    public static function fromParams($source, $params = [])
    {
        $video = Video::fromParams($source, $params);

        $sources = ArrayUtils::pop($params, 'sources');

        $configuration = (new Configuration(Configuration::instance()));
        # set v1 defaults
        $configuration->tag->quotesType       = BaseTag::SINGLE_QUOTES;
        $configuration->tag->sortAttributes   = true;
        $configuration->tag->contentDelimiter = '';

        $configuration->importJson($params);

        $tagAttributes = self::collectAttributesFromParams($params);

        return (new static($video, $sources, $configuration))->setAttributes($tagAttributes);
    }

    /**
     * Sets the fallback content.
     *
     * @param string $content The fallback content.
     *
     * @return $this
     */
    public function fallback($content)
    {
        $this->addContent($content, 'fallback');

        return $this;
    }

    /**
     * Sets the poster attribute.
     *
     * @param string|Image|ImageTransformation $poster The poster image.
     *
     * @return $this
     */
    public function poster($poster)
    {
        if (is_string($poster)) {
            $this->setAttribute('poster', $poster);
        }

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
        if (empty($this->sources)) {
            $attributes['src'] = $this->video;
        }

        if (! array_key_exists('poster', $this->attributes)) {
            $poster                   = new Image($this->video);
            $poster->asset->extension = $this->config->tag->videoPosterFormat;

            $attributes['poster'] = $poster;
        }

        return parent::serializeAttributes($attributes);
    }

    /**
     * Serializes the tag content.
     *
     * @param array $additionalContent The additional content.
     *
     * @return string
     */
    public function serializeContent($additionalContent = [])
    {
        return parent::serializeContent(ArrayUtils::mergeNonEmpty($this->sources, $additionalContent));
    }

    /**
     * Serializes to json.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }

    /**
     * Adds (appends) a transformation.
     *
     * Appended transformation is nested.
     *
     * @param CommonTransformation $transformation The transformation to add.
     *
     * @return static
     */
    public function addTransformation($transformation)
    {
        return $this->applyAssetModification('addTransformation', $transformation);
    }

    /**
     * Adds (chains) a transformation action.
     *
     * @param BaseAction|BaseParameter|mixed $action The transformation action to add.
     *                                               If BaseParameter is provided, it is wrapped with action.
     *
     * @return static
     */
    public function addAction($action)
    {
        return $this->applyAssetModification('addAction', $action);
    }

    /**
     * Sets the property of the asset descriptor.
     *
     * @param string $propertyName  The name of the property.
     * @param mixed  $propertyValue The value of the property.
     *
     * @return $this
     *
     * @internal
     */
    public function setAssetProperty($propertyName, $propertyValue)
    {
        return $this->applyAssetModification('setAssetProperty', $propertyName, $propertyValue);
    }

    /**
     * Sets the Account configuration key with the specified value.
     *
     * @param string $configKey   The configuration key.
     * @param mixed  $configValue THe configuration value.
     *
     * @return $this
     *
     * @internal
     */
    public function setAccountConfig($configKey, $configValue)
    {
        return $this->applyAssetModification('setAccountConfig', $configKey, $configValue);
    }

    /**
     * Sets the Url configuration key with the specified value.
     *
     * @param string $configKey   The configuration key.
     * @param mixed  $configValue THe configuration value.
     *
     * @return $this
     *
     * @internal
     */
    public function setUrlConfig($configKey, $configValue)
    {
        return $this->applyAssetModification('setUrlConfig', $configKey, $configValue);
    }

    /**
     * Applies modification to the asset and to all sources.
     *
     * @param string $modificationName The name of the modification.
     * @param mixed  ...$args          The modification arguments.
     *
     * @return $this
     */
    private function applyAssetModification($modificationName, ...$args)
    {
        $this->video->$modificationName(...$args);

        foreach ($this->sources as $source) {
            $source->video->$modificationName(...$args);
        }

        return $this;
    }
}
