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

/**
 * Trait TransformationCustomFunctionTrait
 *
 * An 'alias' for building a CustomFunction Transformation
 *
 * @api
 */
trait TransformationCustomFunctionTrait
{
    /**
     * Inject a custom function into the image transformation pipeline.
     *
     * @param CustomFunction $customFunction The custom function source
     *
     * @return static
     */
    public function customFunction($customFunction)
    {
        return $this->addAction($customFunction);
    }
}
