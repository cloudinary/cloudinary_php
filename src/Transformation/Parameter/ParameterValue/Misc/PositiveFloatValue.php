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
use InvalidArgumentException;

/**
 * Class PositiveFloatValue
 */
class PositiveFloatValue extends ParameterMultiValue
{
    /**
     * Checks that the parameter is a positive float.
     *
     * @param array $arguments The parameter.
     *
     * @return static
     */
    public function setArguments(...$arguments)
    {
        if (count($arguments) > 1) {
            throw new InvalidArgumentException('No more than 1 argument is expected');
        }

        $floatValue = ArrayUtils::get($arguments, 0);

        if (is_string($floatValue) || $floatValue === null) {
            $this->addValues($floatValue);

            return $this;
        }

        if (! is_numeric($floatValue)) {
            throw new InvalidArgumentException("'$floatValue' should be a number or a string");
        }
        if ($floatValue < 0) {
            throw new InvalidArgumentException("'$floatValue' should be greater than zero");
        }
        if (is_int($floatValue)) {
            $floatValue .= '.0';
        }

        $this->addValues($floatValue);

        return $this;
    }
}
