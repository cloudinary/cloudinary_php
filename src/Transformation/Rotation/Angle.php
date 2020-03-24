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

use Cloudinary\Transformation\Argument\AngleTrait;
use Cloudinary\Transformation\Argument\Degree;
use Cloudinary\Transformation\Parameter\BaseParameter;

/**
 * Class Angle
 */
class Angle extends BaseParameter
{
    const VALUE_CLASS = Degree::class;

    use AngleTrait;

    /**
     * Sets the angle in degrees.
     *
     * @param int|string|array $degree The rotation angle degree.
     */
    public function setAngle(...$degree)
    {
        $this->setParamValue(Degree::angle(...$degree));
    }
}
