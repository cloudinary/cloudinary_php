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

use Cloudinary\Transformation\Parameter\Dimensions\Dimensions;

/**
 * Class Region
 */
class Region extends BaseAction
{
    use RegionTrait;

    /**
     * Region constructor.
     *
     * @param null $x
     * @param null $y
     * @param null $width
     * @param null $height
     */
    public function __construct($x = null, $y = null, $width = null, $height = null)
    {
        parent::__construct();

        $this->x($x)->y($y)->width($width)->height($height);
    }

    /**
     * Internal setter for the point value.
     *
     * @param $value
     *
     * @return static
     *
     * @internal
     */
    public function setPointValue($value)
    {
        if (! isset($this->parameters[Point::getName()])) {
            $this->addParameter(new Point());
        }

        $this->parameters[Point::getName()]->setPointValue($value);

        return $this;
    }

    /**
     * Internal setter for the dimensions.
     *
     * @param $value
     *
     * @return static
     *
     * @internal
     */
    public function setDimension($value)
    {
        if (! isset($this->parameters[Dimensions::getName()])) {
            $this->addParameter(new Dimensions());
        }

        $this->parameters[Dimensions::getName()]->addParameter($value);

        return $this;
    }
}
