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
 * Trait FlagQualifierTrait
 *
 * @api
 */
trait FlagQualifierTrait
{
    /**
     * Sets the flag.
     *
     * @param string             $flagName The name of the flag.
     * @param string|array|mixed $value    An optional value of the flag.
     *
     * @return FlagQualifier
     */
    public static function flag($flagName, $value = null)
    {
        return new FlagQualifier($flagName, $value);
    }
}
