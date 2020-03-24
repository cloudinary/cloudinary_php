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

/**
 * Class Rotation
 */
class Rotate extends BaseAction
{
    use AngleTrait;

    /**
     * Sets the rotation angle.
     *
     * @param mixed $degree The degrees of the angle.
     */
    public function setAngle(...$degree)
    {
        $this->addParameter(Degree::angle(...$degree));
    }

    /**
     * Named constructor.
     *
     * @param mixed $degree The degree of the angle.
     *
     * @return static
     */
    protected static function createWithDegree(...$degree)
    {
        return new static(Angle::angle(...$degree));
    }
}
