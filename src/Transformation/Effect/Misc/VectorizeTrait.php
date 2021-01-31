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
 * Trait VectorizeTrait
 *
 * @api
 */
trait VectorizeTrait
{
    /**
     * Limits the number of colors in the image.
     *
     * @param int $numOfColors The number of colors. (Range: 2 to 30, Server default: 10)
     *
     * @return $this
     */
    public function numOfColors($numOfColors = null)
    {
        $this->setSimpleNamedValue(VectorizeValue::COLORS, $numOfColors);

        return $this;
    }

    /**
     * Controls the level of detail.
     *
     * @param float $level The level of detail. Specify either a percentage of the original image (Range: 0.0 to 1.0)
     *                     or an absolute number of pixels (Range: 0 to 1000). (Server default: 300)
     *
     * @return $this
     */
    public function detailsLevel($level)
    {
        $this->setSimpleNamedValue(VectorizeValue::DETAIL, $level);

        return $this;
    }

    /**
     * Suppresses speckles up to the given size.
     *
     * @param float $size The size of speckles to suppress. Specify either a percentage of the original image
     *                    (Range: 0.0 to 1.0) or an absolute number of pixels (Range: 0 to 100, Server default: 2)
     *
     * @return $this
     */
    public function despeckleLevel($size)
    {
        $this->setSimpleNamedValue(VectorizeValue::DESPECKLE, $size);

        return $this;
    }

    /**
     * Controls the Bezier curve optimization.
     *
     * @param int $value The optimization value. Specify 100 for least optimization and the largest file.
     *                   (Range: 0 to 100, Server default: 100).
     *
     * @return $this
     */
    public function paths($value)
    {
        $this->setSimpleNamedValue(VectorizeValue::PATHS, $value);

        return $this;
    }

    /**
     * Controls the corner threshold. 100 for no smoothing (polygon corners), 0 for completely smooth corners.
     *
     * @param int $threshold The corner threshold.  Specify 100 for no smoothing (polygon corners), 0 for completely
     *                       smooth corners. (Range: 0 to 100, Default: 25)
     *
     * @return $this
     */
    public function cornersLevel($threshold)
    {
        $this->setSimpleNamedValue(VectorizeValue::CORNERS, $threshold);

        return $this;
    }
}
