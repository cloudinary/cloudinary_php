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
 * Trait CustomFunctionParamTrait
 *
 * @api
 */
trait CustomFunctionParamTrait
{
    /**
     * Injects a custom function into the image transformation pipeline.
     *
     * @param string $source Source of this custom function
     * @param string $type   Type of this custom function ({@see CustomFunction::REMOTE} or {@see CustomFunction::WASM})
     * @param bool   $isPre  Preprocess custom function. Only remote functions are supported for preprocess
     *
     * @return CustomFunction
     */
    public static function customFunction($source, $type = null, $isPre = false)
    {
        return new CustomFunction($source, $type, $isPre);
    }
}
