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

use Cloudinary\ClassUtils;

/**
 * Represents an embedded smart object in a Photoshop document.
 *
 * **Learn more**:
 * <a
 * href=https://cloudinary.com/documentation/paged_and_layered_media#extract_the_original_content_of_an_embedded_object
 * target="_blank">Extract the original content of an embedded Photoshop object</a>
 *
 * @api
 */
abstract class SmartObject
{
    use PageNameTrait;
    use PageNamesTrait;
    use PageNumberTrait;

    /**
     * Internal named constructor.
     *
     * @param $value
     *
     * @return PageParam
     *
     * @internal
     */
    public static function createWithPageParam(...$value)
    {
        return ClassUtils::verifyVarArgsInstance($value, SmartObjectParam::class);
    }

    /**
     * Internal named constructor.
     *
     * @param $value
     *
     * @return PageParam
     *
     * @internal
     */
    public static function createWithNamedPageParam(...$value)
    {
        return static::createWithPageParam(new LayerName(...$value));
    }
}
