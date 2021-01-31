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
 * Defines the objects that can be focused on.
 *
 * @api
 */
abstract class AutoFocus
{
    /**
     * @param mixed $object
     *
     * @return string
     */
    public static function focusOn($object)
    {
        return ClassUtils::verifyInstance($object, AutoGravityObject::class);
    }
}
