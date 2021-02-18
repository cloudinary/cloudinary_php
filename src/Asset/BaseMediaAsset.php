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
use Cloudinary\Transformation\BaseAction;
use Cloudinary\Transformation\CommonTransformation;
use Cloudinary\Transformation\CommonTransformationInterface;
use Cloudinary\Transformation\ImageTransformation;
use Cloudinary\Transformation\Qualifier\BaseQualifier;
use Cloudinary\Transformation\Transformation;
use Cloudinary\Transformation\VideoTransformation;

/**
 * Class BaseMediaAsset
 *
 * @api
 */
abstract class BaseMediaAsset extends BaseAsset implements CommonTransformationInterface
{
    use MediaAssetFinalizerTrait;

    /**
     * @var ImageTransformation|VideoTransformation|Transformation $transformation The transformation.
     */
    public $transformation;

    /**
     * BaseMediaAsset constructor.
     *
     * @param      $source
     * @param Configuration|string|array|null $configuration The Configuration source.
     */
    public function __construct($source, $configuration = null)
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
     * @return static
     */
    public static function fromParams($source, $params = [])
    {
        $asset = parent::fromParams($source, $params);

        $asset->setTransformation(Transformation::fromParams($params));

        return $asset;
    }


    /**
     * Sets the transformation.
     *
     * @param CommonTransformation $transformation The transformation
     *
     * @return static
     */
    public function setTransformation(CommonTransformation $transformation)
    {
        $this->transformation = $transformation;

        return $this;
    }

    /**
     * Gets the asset transformation.
     *
     * @return ImageTransformation|VideoTransformation|Transformation
     */
    abstract public function getTransformation();

    /**
     * Adds (appends) a transformation.
     *
     * Appended transformation is nested.
     *
     * @param CommonTransformation|array|string $transformation The transformation to add.
     *
     * @return $this
     */
    public function addTransformation($transformation)
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
     * @return static
     */
    public function addAction($action)
    {
        $this->getTransformation()->addAction($action);

        return $this;
    }

    /**
     * Internal pre-serialization helper.
     *
     * @param CommonTransformation|string $withTransformation Optional transformation that can be appended/used instead.
     * @param bool                        $append             Whether to append or use the provided transformation.
     *
     * @return array
     */
    protected function prepareUrlParts($withTransformation = null, $append = true)
    {
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
     * @param CommonTransformation|string $withTransformation Optional transformation that can be appended/used instead.
     * @param bool                        $append             Whether to append or use the provided transformation.
     *
     * @return string
     */
    public function toUrl($withTransformation = null, $append = true)
    {
        return $this->finalizeUrl(
            ArrayUtils::implodeUrl($this->prepareUrlParts($withTransformation, $append))
        );
    }
}
