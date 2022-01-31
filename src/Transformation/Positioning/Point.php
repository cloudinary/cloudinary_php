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
 * Class Point
 */
class Point extends BaseAction
{
    use PointTrait;

    /**
     * Point constructor.
     *
     * @param null $x
     * @param null $y
     */
    public function __construct($x = null, $y = null)
    {
        parent::__construct();

        $this->point($x, $y);
    }

    /**
     * Internal setter for the point value.
     *
     * @param mixed $value
     *
     * @return static
     *
     * @internal
     */
    public function setPointValue($value)
    {
        return $this->addQualifier($value);
    }
}
