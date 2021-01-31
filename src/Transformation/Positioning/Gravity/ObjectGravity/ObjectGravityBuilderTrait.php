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
 * Trait ObjectGravityBuilderTrait
 *
 * @api
 */
trait ObjectGravityBuilderTrait
{
    /**
     * Creates a new instance of the ObjectGravity.
     *
     * @param mixed ...$objects The names of the objects.
     *
     * @return string
     */
    public static function object(...$objects)
    {
        return new ObjectGravity(...$objects);
    }
}
