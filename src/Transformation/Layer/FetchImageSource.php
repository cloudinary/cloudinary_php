<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation;

use Cloudinary\Asset\AssetDescriptor;
use Cloudinary\Asset\BaseAsset;
use Cloudinary\ClassUtils;

/**
 * Defines how to manipulate an image layer.
 *
 * **Learn more**: <a
 * href=https://cloudinary.com/documentation/image_transformations#image_and_text_overlays target="_blank">
 * Image overlays</a>
 *
 * @api
 */
class FetchImageSource extends ImageSource
{
    /**
     * Sets the source of the layer.
     *
     * @param string|BaseSourceQualifier $source The source.
     *
     * @return $this
     */
    public function setSource($source)
    {

        if ($source instanceof BaseSourceQualifier) {
            $this->getSourceQualifier()->setQualifierValue($source->getValue());

            return $this;
        }

        $this->getSourceQualifier()->setQualifierValue((string)$source);


        return $this;
    }

    /**
     * Gets the layer qualifier.
     *
     * @return ImageSourceQualifier
     *
     * @internal
     */
    protected function getSourceQualifier()
    {
        if (! isset($this->qualifiers['source'])) {
            $this->qualifiers['source'] = new FetchSourceQualifier(null);
        }

        return $this->qualifiers['source'];
    }
}
