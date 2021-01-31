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
 * Trait CustomFunctionQualifierTrait
 *
 * @api
 */
trait CustomFunctionQualifierTrait
{
    /**
     * Injects a custom function into the image transformation pipeline.
     *
     * @param string $source     Source of this custom function
     * @param string $type       The type of custom function (CustomFunction::REMOTE or CustomFunction::WASM).
     * @param bool   $preprocess Preprocess custom function. Only remote functions are supported for preprocess
     *
     * @return CustomFunction
     *
     * @see CustomFunction::REMOTE
     * @see CustomFunction::WASM
     */
    public static function customFunction($source, $type = null, $preprocess = false)
    {
        return new CustomFunction($source, $type, $preprocess);
    }
}
