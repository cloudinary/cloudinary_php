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
 * Class MinMaxRange
 */
class MinMaxRange extends QualifierMultiValue
{
    const VALUE_DELIMITER = '-';

    /**
     * @var array $argumentOrder The order of the arguments.
     */
    protected $argumentOrder = ['min', 'max'];

    /**
     * MinMaxRange constructor.
     *
     * @param      $min
     * @param null $max
     */
    public function __construct($min, $max = null)
    {
        parent::__construct();

        $this->min($min);
        $this->max($max);
    }

    /**
     * Sets the minimum value.
     *
     * @param int $min The minimum value.
     *
     * @return $this
     */
    public function min($min)
    {
        return $this->setSimpleValue('min', $min);
    }

    /**
     * Sets the maximum value.
     *
     * @param int $max The maximum value.
     *
     * @return $this
     */
    public function max($max)
    {
        return $this->setSimpleValue('max', $max);
    }
}
