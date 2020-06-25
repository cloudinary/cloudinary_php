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

use Cloudinary\StringUtils;
use Cloudinary\Transformation\Parameter\BaseParameter;

/**
 * Calls a custom function.
 *
 * **Learn more**: <a href="https://cloudinary.com/documentation/custom_functions" target="_blank">Custom functions</a>
 *
 * @api
 */
class CustomFunction extends BaseParameter
{
    use CustomFunctionTrait;

    /**
     * @var string $name Serialisation name.
     */
    protected static $name = 'custom_function';

    /**
     * @var string $key Serialization key.
     */
    protected static $key = 'fn';

    /**
     * WASM.
     *
     * @var string
     */
    const WASM = 'wasm';
    /**
     * Remote.
     *
     * @var string
     */
    const REMOTE = 'remote';

    /**
     * CustomFunction constructor.
     *
     * @param string $source Source of this custom function
     * @param string $type   The type of custom function (CustomFunction::REMOTE or CustomFunction::WASM).
     * @param bool   $isPre  Preprocess custom function. Only remote functions are supported for preprocess
     *
     * @see CustomFunction::REMOTE
     * @see CustomFunction::WASM
     */
    public function __construct($source, $type = null, $isPre = false)
    {
        parent::__construct(
            $isPre ? "pre:{$type}" : $type,
            $type === self::WASM ? $source : StringUtils::base64UrlEncode($source)
        );
    }
}
