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
 * Rotates or flips an image or video by the specified number of degrees, or automatically (images only) according to its orientation or available metadata.
 *
 * **Learn more**:
 * <a href=https://cloudinary.com/documentation/image_transformations#rotating_images
 * target="_blank">Rotating images</a> |
 * <a href=https://cloudinary.com/documentation/video_manipulation_and_delivery#rotating_videos
 * target="_blank">Rotating videos</a>
 *
 *
 * @api
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
