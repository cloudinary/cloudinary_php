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

use Cloudinary\Transformation\BaseArgument;

/**
 * Class RangeArgument
 */
class RangeArgument extends BaseArgument
{
    const ARG_INNER_VALUE_DELIMITER = '-';

    /**
     * RangeArgument constructor.
     *
     * @param int|string $start The start of the range.
     * @param int|string $end   The end of the range.
     */
    public function __construct($start, $end = null)
    {
        parent::__construct($start, $end);
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        // Note that here we allow empty values, to get open range, for example: "1-"
        return implode(self::ARG_INNER_VALUE_DELIMITER, $this->argMultiValue);
    }
}
