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
 * Defines the category of transformation to perform.
 *
 * @api
 */
class Action extends BaseAction
{
    /**
     * Action named constructor.
     *
     * @param string $genericActionString
     *
     * @return BaseAction
     */
    public static function generic($genericActionString = null)
    {
        return (new static())->setGenericAction($genericActionString);
    }
}
