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
 * Trait BorderStyleTrait
 *
 * @api
 */
trait BorderStyleTrait
{
    /**
     * Adds a border around the image.
     *
     * @return Border
     */
    public static function solid()
    {
        return (new Border())->style('solid');
    }
}
