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
 * Trait FormatParamTrait
 *
 * @api
 */
trait FormatParamTrait
{
    /**
     * Sets the file format.
     *
     * @param string $format The file format.
     *
     * @return FormatParam
     */
    public static function format($format)
    {
        return ClassUtils::verifyInstance($format, FormatParam::class);
    }
}
