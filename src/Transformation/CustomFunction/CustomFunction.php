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
 * Class CustomFunction
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
    protected static $key  = 'fn';

    const WASM   = 'wasm';
    const REMOTE = 'remote';

    /**
     * CustomFunction constructor.
     *
     * @param string $source Source of this custom function
     * @param string $type   Type of this custom function ({@see CustomFunction::REMOTE} or {@see CustomFunction::WASM})
     * @param bool   $isPre  Preprocess custom function. Only remote functions are supported for preprocess
     */
    public function __construct($source, $type = null, $isPre = false)
    {
        parent::__construct(
            $isPre ? "pre:{$type}" : $type,
            $type === self::WASM ? $source : StringUtils::base64UrlEncode($source)
        );
    }
}
