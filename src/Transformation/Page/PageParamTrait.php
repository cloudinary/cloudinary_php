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
 * Trait PageParamTrait
 *
 * @api
 */
trait PageParamTrait
{
    /**
     * Extracts the specified page(s).
     *
     * @param int|string|array|mixed $page The page(s) to extract.
     *
     * @return PageParam
     */
    public static function page($page)
    {
        return new PageParam($page);
    }
}
