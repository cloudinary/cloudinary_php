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
use Cloudinary\Configuration\AssetConfigTrait;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Transformation\BaseAction;
use Cloudinary\Transformation\CommonTransformation;
use Cloudinary\Transformation\ImageTransformation;
use Cloudinary\Transformation\ImageTransformationInterface;
use Cloudinary\Transformation\ImageTransformationTrait;
use Cloudinary\Transformation\Parameter\BaseParameter;

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
     * @param string|Image                    $image                    The Public ID or Image instance
     * @param Configuration|string|array|null $configuration            The Configuration source.
     * @param ImageTransformation             $additionalTransformation The additional transformation.
     */
    public function __construct($image, $configuration = null, $additionalTransformation = null)
    {
        parent::__construct($configuration);

        $this->image($image, $this->config);

        $this->srcset = new SrcSet($this->image, $this->config);

        $this->additionalTransformation = $additionalTransformation;
    }

    /**
     * Creates a new base image tag from the provided source and an array of parameters.
     *
     * @param string $source The public ID of the asset.
     * @param array  $params The asset parameters.
     *
     * @return mixed
     */
    public static function fromParams($source, $params = [])
    {
        $image = Image::fromParams($source, $params);

        $configuration = (new Configuration(Configuration::instance()));
        # set v1 defaults
        $configuration->tag->quotesType       = BaseTag::SINGLE_QUOTES;
        $configuration->tag->sortAttributes   = true;
        $configuration->tag->voidClosingSlash = true;

        ArrayUtils::addNonEmpty($params, 'responsive_breakpoints', ArrayUtils::pop($params, 'srcset'));

        $configuration->importJson($params);

        $tagAttributes = self::collectAttributesFromParams($params);

        ArrayUtils::addNonEmptyFromOther($tagAttributes, 'width', $params);
        ArrayUtils::addNonEmptyFromOther($tagAttributes, 'height', $params);

        return (new static($image, $configuration))->setAttributes($tagAttributes);
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
        $this->image = new Image($image, $configuration);

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
     * @param BaseAction|BaseParameter|mixed $action The transformation action to add.
     *                                               If BaseParameter is provided, it is wrapped with action.
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
        $this->image->setAccountConfig($configKey, $configValue);

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
