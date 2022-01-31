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

use Cloudinary\Transformation\Qualifier\Dimensions\Dimensions;

/**
 * Class Region
 */
class Region extends BaseAction
{
    const FACES   = "faces";
    const GRAVITY = "gravity";
    const CUSTOM  = "custom";

    use RegionTrait;
    use PixelEffectRegionTrait;

    /**
     * Region constructor.
     *
     * @param int|string $x
     * @param int|string $y
     * @param int|string $width
     * @param int|string $height
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
        if (! isset($this->qualifiers[Point::getName()])) {
            $this->addQualifier(new Point());
        }

        $this->qualifiers[Point::getName()]->setPointValue($value);

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
        if (! isset($this->qualifiers[Dimensions::getName()])) {
            $this->addQualifier(new Dimensions());
        }

        $this->qualifiers[Dimensions::getName()]->addQualifier($value);

        return $this;
    }

    /**
     * @return string
     */
    public function getRegionType()
    {
        if (isset($this->qualifiers[GravityQualifier::getName()])) {
            $gravityQualifier = $this->qualifiers[GravityQualifier::getName()];

            if ((string)$gravityQualifier->getValue() === FocalGravity::FACES) {
                return self::FACES;
            }

            return self::GRAVITY;
        }

        return self::CUSTOM;
    }
}
