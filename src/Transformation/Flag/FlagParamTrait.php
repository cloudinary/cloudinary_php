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
 * Trait FlagParamTrait
 *
 * @api
 */
trait FlagParamTrait
{
    /**
     * Sets the flag.
     *
     * @param string             $flagName The name of the flag.
     * @param string|array|mixed $value    An optional value of the flag.
     *
     * @return FlagParameter
     */
    public static function flag($flagName, $value = null)
    {
        return new FlagParameter($flagName, $value);
    }
}
