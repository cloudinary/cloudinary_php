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
use Cloudinary\ClassUtils;
use Cloudinary\Configuration\AssetConfigTrait;
use Cloudinary\Configuration\Configuration;
use Cloudinary\StringUtils;
use Cloudinary\Transformation\BaseAction;
use Cloudinary\Transformation\CommonTransformation;
use Cloudinary\Transformation\ImageTransformation;
use Cloudinary\Transformation\ImageTransformationInterface;
use Cloudinary\Transformation\ImageTransformationTrait;
use Cloudinary\Transformation\Qualifier\BaseQualifier;

/**
 * Class BaseImageTag
 *
 * @api
 */
abstract class BaseImageTag extends BaseTag implements ImageTransformationInterface
{
    use ImageTagDeliveryTypeTrait;
    use ImageTransformationTrait;
    use AssetDescriptorTrait;
    use AssetConfigTrait;

    const IS_VOID = true;

    /**
     * @var Image $image The image of the tag.
     */
    public $image;

    /**
     * @var SrcSet $srcset The srcset of the tag.
     */
    public $srcset;

    /**
     * @var ImageTransformation $additionalTransformation Additional transformation to be applied on the tag image.
     */
    public $additionalTransformation;

    /**
     * BaseImageTag constructor.
     *
     * @param string|Image                    $source                   The Public ID or Image instance
     * @param Configuration|string|array|null $configuration            The Configuration source.
     * @param ImageTransformation             $additionalTransformation The additional transformation.
     */
    public function __construct($source, $configuration = null, $additionalTransformation = null)
    {
        parent::__construct($configuration);

        $this->image($source, $this->config);

        $this->srcset = new SrcSet($this->image, $this->config);

        $this->additionalTransformation = $additionalTransformation;
    }

    /**
     * Creates a new base image tag from the provided source and an array of parameters.
     *
     * @param string $source The public ID of the asset.
     * @param array  $params The asset parameters.
     *
     * @return BaseImageTag
     */
    public static function fromParams($source, $params = [])
    {
        $configuration = self::fromParamsDefaultConfig();

        $configuration->tag->prependSrcAttribute = true;

        if (array_key_exists('srcset', $params) && is_array($params['srcset'])) {
            if (array_key_exists('sizes', $params['srcset']) && $params['srcset']['sizes']) {
                $configuration->tag->sizes = $params['srcset']['sizes'];
            }
            ArrayUtils::addNonEmpty($params, 'responsive_breakpoints', ArrayUtils::pop($params, 'srcset'));
        }

        $configuration->importJson($params);

        self::handleResponsive($params, $configuration);

        $image = Image::fromParams($source, $params);

        $tagAttributes = self::collectAttributesFromParams($params);

        TagUtils::handleSpecialAttributes($tagAttributes, $params, $configuration);

        return (new static($image, $configuration))->setAttributes($tagAttributes);
    }

    /**
     * @param array         $params
     * @param Configuration $configuration
     */
    public static function handleResponsive(&$params, $configuration)
    {
        if ($configuration->url->responsiveWidth) {
            $configuration->tag->responsive = true;
        }

        $width = ArrayUtils::get($params, 'width');
        if (! empty($width) && StringUtils::startsWith($width, 'auto')) {
            $configuration->tag->responsive = true;
        }

        $dpr = ArrayUtils::get($params, 'dpr');
        if (! empty($dpr) && StringUtils::startsWith($dpr, 'auto')) {
            $configuration->tag->hidpi = true;
        }

        $configuration->tag->clientHints = ArrayUtils::pop($params, 'client_hints', $configuration->tag->clientHints);
    }

    /**
     * Imports (merges) the configuration.
     *
     * @param Configuration|string|array|null $configuration The Configuration source.
     *
     * @return static
     */
    public function importConfiguration($configuration)
    {
        parent::importConfiguration($configuration);

        $this->image->importConfiguration($configuration);

        return $this;
    }

    /**
     * Sets the image.
     *
     * @param mixed         $image         The public ID or Image asset.
     * @param Configuration $configuration The configuration instance.
     *
     * @return static
     */
    public function image($image, $configuration = null)
    {
        if ($configuration === null) {
            $configuration = $this->config;
        }

        $this->image = ClassUtils::forceInstance($image, Image::class, null, $configuration);

        return $this;
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
        $this->image->addTransformation($transformation);

        return $this;
    }

    /**
     * Adds (chains) a transformation action.
     *
     * @param BaseAction|BaseQualifier|mixed $action The transformation action to add.
     *                                               If BaseQualifier is provided, it is wrapped with action.
     *
     * @return static
     */
    public function addAction($action)
    {
        $this->image->addAction($action);

        return $this;
    }

    /**
     * Explicitly sets the breakpoints.
     *
     * @param array|null $breakpoints The breakpoints.
     *
     * @return $this
     */
    public function breakpoints(array $breakpoints = null)
    {
        $this->srcset->breakpoints($breakpoints);

        return $this;
    }

    /**
     * Defines whether to use auto optimal breakpoints.
     *
     * @param bool $autoOptimalBreakpoints Indicates whether to use auto optimal breakpoints.
     *
     * @return $this
     */
    public function autoOptimalBreakpoints($autoOptimalBreakpoints = true)
    {
        $this->srcset->autoOptimalBreakpoints($autoOptimalBreakpoints);

        return $this;
    }

    /**
     * Sets the image relative width.
     *
     * @param float $relativeWidth The percentage of the screen that the image occupies..
     *
     * @return $this
     */
    public function relativeWidth($relativeWidth = 1.0)
    {
        $this->srcset->relativeWidth($relativeWidth);

        $this->config->tag->relativeWidth = $relativeWidth;

        return $this;
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
        $this->image->setAssetProperty($propertyName, $propertyValue);

        return $this;
    }

    /**
     * Sets the Cloud configuration key with the specified value.
     *
     * @param string $configKey   The configuration key.
     * @param mixed  $configValue THe configuration value.
     *
     * @return $this
     *
     * @internal
     */
    public function setCloudConfig($configKey, $configValue)
    {
        $this->image->setCloudConfig($configKey, $configValue);

        return $this;
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
        $this->image->setUrlConfig($configKey, $configValue);

        return $this;
    }
}
