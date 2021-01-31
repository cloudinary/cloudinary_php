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
 * Class Shear
 */
class Shear extends EffectQualifier
{
    /**
     * @var array $valueOrder The order of the values.
     */
    protected $valueOrder = [0, 'skew_x', 'skew_y'];

    /**
     * Shear constructor.
     *
     * @param null $skewX
     * @param      $skewY
     */
    public function __construct($skewX = null, $skewY = null)
    {
        parent::__construct(ReshapeQualifier::SHEAR);

        $this->skewX($skewX);
        $this->skewY($skewY);
    }

    /**
     * Sets the angle of skew on the x-axis.
     *
     * @param float $value The angle of skew on the x-axis in degrees.
     *
     * @return Shear
     */
    public function skewX($value)
    {
        $this->value->setSimpleValue('skew_x', $value);

        return $this;
    }

    /**
     * Sets the angle of skew on the y-axis.
     *
     * @param float $value The angle of skew on the y-axis in degrees.
     *
     * @return Shear
     */
    public function skewY($value)
    {
        $this->value->setSimpleValue('skew_y', $value);

        return $this;
    }
}
