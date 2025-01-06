<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Asset;

use Cloudinary\ArrayUtils;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Configuration\TagConfig;
use Cloudinary\Exception\ConfigurationException;
use Cloudinary\Transformation\BaseAction;
use Cloudinary\Transformation\CommonTransformation;
use Cloudinary\Transformation\CommonTransformationInterface;
use Cloudinary\Transformation\Delivery;
use Cloudinary\Transformation\ImageTransformation;
use Cloudinary\Transformation\Qualifier\BaseQualifier;
use Cloudinary\Transformation\Transformation;
use Cloudinary\Transformation\VideoTransformation;
use Psr\Http\Message\UriInterface;

/**
 * Class BaseMediaAsset
 *
 * @api
 */
abstract class BaseMediaAsset extends BaseAsset implements CommonTransformationInterface
{
    use MediaAssetFinalizerTrait;

    /**
     * @var ImageTransformation|VideoTransformation|CommonTransformation $transformation The transformation.
     */
    public $transformation;

    /**
     * BaseMediaAsset constructor.
     *
     * @param mixed                           $source        The source.
     * @param Configuration|string|array|null $configuration The Configuration source.
     */
    public function __construct(mixed $source, Configuration|string|array|null $configuration = null)
    {
        parent::__construct($source, $configuration);

        if ($source instanceof self && ! empty($source->transformation)) {
            $transformation = clone $source->transformation;

            if ($source instanceof $this) {
                $this->setTransformation($transformation);
            } else {
                $this->addTransformation($transformation); // nest and keep current transformation type
            }
        }
    }

    /**
     * Creates a new media asset from the provided public and array of parameters.
     *
     * @param string $source The public ID of the asset.
     * @param array  $params The media asset parameters.
     *
     */
    public static function fromParams(string $source, array $params): static
    {
        $params = self::setFormatParameter($params);

        $asset = parent::fromParams($source, $params);

        $asset->setTransformation(Transformation::fromParams($params));

        return $asset;
    }


    /**
     * Sets the transformation.
     *
     * @param CommonTransformation $transformation The transformation
     *
     */
    public function setTransformation(CommonTransformation $transformation): static
    {
        $this->transformation = $transformation;

        return $this;
    }

    /**
     * Gets the asset transformation.
     *
     */
    abstract public function getTransformation(): CommonTransformation;

    /**
     * Adds (appends) a transformation in URL syntax to the current chain. A transformation is a set of instructions
     * for adjusting images or videos—such as resizing, cropping, applying filters, adding overlays, or optimizing
     * formats. For a detailed listing of all transformations, see the [Transformation
     * Reference](https://cloudinary.com/documentation/transformation_reference) or the [PHP
     * reference](https://cloudinary.com/documentation/sdks/php/php-transformation-builder/index.html).
     *
     * Appended transformation is nested.
     *
     * @param CommonTransformation|array|string $transformation The transformation to add.
     *
     * @return $this
     */
    public function addTransformation($transformation): static
    {
        $this->getTransformation()->addTransformation($transformation);

        return $this;
    }

    /**
     * Adds (chains) a transformation action.
     *
     * @param BaseAction|BaseQualifier|mixed $action The transformation action to add.
     *                                               If BaseQualifier is provided, it is wrapped with action.
     *
     */
    public function addAction($action): static
    {
        $this->getTransformation()->addAction($action);

        return $this;
    }

    /**
     * Sets the asset format.
     *
     * For non-fetched assets sets the filename extension.
     * For remotely fetched assets sets the 'f_' transformation parameter.
     *
     * @param string $format         The format to set.
     * @param ?bool  $useFetchFormat Whether to force fetch format behavior.
     */
    public function setFormat(string $format, ?bool $useFetchFormat = false): static
    {
        if ($useFetchFormat || $this->asset->deliveryType == DeliveryType::FETCH) {
            $this->addTransformation(Delivery::format($format));
        } else {
            $this->asset->extension = $format;
        }

        return $this;
    }

    /**
     * Internal pre-serialization helper.
     *
     * @param mixed $withTransformation Optional transformation that can be appended/used instead.
     * @param bool  $append             Whether to append or use the provided transformation.
     *
     */
    protected function prepareUrlParts(
        mixed $withTransformation = null,
        bool $append = true
    ): array {
        $urlParts = parent::prepareUrlParts();
        $urlParts = ArrayUtils::insertAt(
            $urlParts,
            'version',
            'transformation',
            $this->finalizeTransformation($withTransformation, $append),
            false
        );

        $urlParts['signature'] = $this->finalizeSimpleSignature(); // fix signature

        return $urlParts;
    }

    /**
     * Serializes to the URL string.
     *
     * @param string|CommonTransformation|array|null $withTransformation Optional transformation that can be
     *                                                                   appended/used instead.
     * @param bool                                   $append             Whether to append or use the provided
     *                                                                   transformation.
     *
     * @throws ConfigurationException
     */
    public function toUrl(
        CommonTransformation|string|array|null $withTransformation = null,
        bool $append = true
    ): UriInterface {
        return $this->finalizeUrl(
            ArrayUtils::implodeUrl($this->prepareUrlParts($withTransformation, $append))
        );
    }

    /**
     * Helper for `fromParams` method.
     *
     * When user specifies the `format` parameter, it is usually set as a file extension.
     * When fetching remote URLs, this is converted to a `fetch_format` transformation parameter(f_) instead.
     *
     * @param array $params The parameters to check.
     *
     * @return array the resulting parameters.
     */
    private static function setFormatParameter(array $params): array
    {
        if (ArrayUtils::get($params, DeliveryType::KEY) !== DeliveryType::FETCH
            && ! ArrayUtils::get($params, TagConfig::USE_FETCH_FORMAT, false)
        ) {
            return $params;
        }

        if (array_key_exists('format', $params)) {
            ArrayUtils::setDefaultValue($params, 'fetch_format', ArrayUtils::pop($params, 'format'));
        }

        return $params;
    }
}
