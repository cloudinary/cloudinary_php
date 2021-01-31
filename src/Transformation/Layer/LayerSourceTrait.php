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

use Cloudinary\Transformation\Qualifier\BaseQualifier;

/**
 * Trait LayerSourceTrait
 */
trait LayerSourceTrait
{
    /**
     * Sets the source of the layer.
     *
     * @param string|SourceValue|BaseSourceQualifier $source The source.
     *
     * @return static
     */
    public function source($source)
    {
        return $this->setSource($source);
    }

    /**
     * Sets the source of the layer.
     *
     * @param string|QualifierMultiValue|BaseSourceQualifier $source The source.
     *
     * @return static
     *
     * @internal
     *
     */
    public function setSource($source)
    {
        if ($source instanceof BaseQualifier) {
            $source = $source->getValue();
        }

        return $this->setSimpleValue('source', $source);
    }
}
