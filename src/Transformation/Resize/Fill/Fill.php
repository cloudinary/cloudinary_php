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
 * Class Fill
 */
class Fill extends BaseResizeAction
{
    use FillTrait;
    use FocalGravityTrait;
    use PointTrait;

    /**
     * Fill constructor.
     *
     * @param      $cropMode
     * @param null $width
     * @param null $height
     * @param null $gravity
     */
    public function __construct($cropMode, $width = null, $height = null, $gravity = null)
    {
        parent::__construct($cropMode, $width, $height);

        $this->gravity($gravity);
    }


    /**
     * Internal setter for offset.
     *
     * @param $value
     *
     * @return $this
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
}
