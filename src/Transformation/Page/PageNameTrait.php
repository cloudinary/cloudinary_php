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

use Cloudinary\Transformation\Argument\IndexedArgument;

/**
 * Trait PageNameTrait
 *
 * @api
 */
trait PageNameTrait
{
    /**
     * Creates an instance using the name.
     *
     * @param string $name The name
     * @param int $index The optional index.
     *
     * @return static
     */
    public static function name($name, $index = null)
    {
        return static::createWithNamedPageParam(new IndexedArgument($name, $index));
    }
}
