<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Argument;

/**
 * Class GenericArgument
 */
class GenericArgument extends GenericNamedArgument
{
    /**
     * GenericArgument constructor.
     *
     * @param        $name
     * @param        $value
     * @param string $innerValueDelimiter
     */
    public function __construct($name, $value, $innerValueDelimiter = self::ARG_INNER_VALUE_DELIMITER)
    {
        parent::__construct($name, $value, null, $innerValueDelimiter);
    }
}
