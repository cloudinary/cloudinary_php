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
use Cloudinary\Transformation\Argument\AngleTrait;
use Cloudinary\Transformation\Argument\Degree;
use Cloudinary\Transformation\Argument\RotationModeTrait;
use Cloudinary\Transformation\Qualifier\BaseQualifier;

/**
 * Class Angle
 */
class Angle extends BaseQualifier
{
    const VALUE_CLASS = Degree::class;

    use AngleTrait;
    use RotationModeTrait;

    /**
     * Sets the angle in degrees.
     *
     * @param int|string|array $degree The rotation angle degree.
     */
    public function setAngle(...$degree)
    {
        $this->setQualifierValue(Degree::byAngle(...$degree));
    }

    /**
     * @param $value
     *
     * @return Angle
     */
    public static function fromParams($value)
    {
        return new self(...ArrayUtils::build($value));
    }
}
