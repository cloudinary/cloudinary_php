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

use Cloudinary\ArrayUtils;
use Cloudinary\Transformation\Argument\BaseNamedArgument;

/**
 * Class BaseArgument
 */
abstract class BaseArgument extends BaseNamedArgument
{
    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        return ArrayUtils::implodeFiltered(
            static::ARG_INNER_VALUE_DELIMITER,
            ArrayUtils::build(ArrayUtils::flatten($this->argMultiValue))
        );
    }
}
