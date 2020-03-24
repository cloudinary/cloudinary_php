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

use Cloudinary\Transformation\Argument\BaseNamedArgument;

/**
 * Class SmartObjectName
 */
class SmartObjectName extends BaseNamedArgument
{
    const ARG_NAME_VALUE_DELIMITER  = ':';
    const ARG_INNER_VALUE_DELIMITER = ';';

    /**
     * @var string $name The name.
     */
    protected static $name = 'embedded';
}
