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

/**
 * Class MatrixValue
 */
class MatrixValue extends BaseArgument
{
    protected static $name = "matrix";

    public function __construct(...$value)
    {
        // If instead of a matrix (array of arrays), we get a flat list of numbers,
        // just wrap it with another array for consistency.
        if (count($value) > 1 && !is_array($value[0])) {
            $value = [$value];
        }

        parent::__construct($value);
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        return ArrayUtils::implodeQualifierValues(...array_merge(...ArrayUtils::flatten($this->argMultiValue)));
    }
}
