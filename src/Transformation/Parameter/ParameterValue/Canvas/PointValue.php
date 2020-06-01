<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Argument;

use Cloudinary\Transformation\BaseComponent;

/**
 * Class PointValue
 */
class PointValue extends BaseComponent
{
    /**
     * @var int|string $x The x dimension of the point.
     */
    protected $x;

    /**
     * @var int|string $y The y dimension of the point.
     */
    protected $y;

    /**
     * PointValue constructor.
     *
     * @param null $x
     * @param null $y
     */
    public function __construct($x = null, $y = null)
    {
        parent::__construct();

        $this->x($x)->y($y);
    }

    /**
     * Sets the x dimension of the point.
     *
     * @param int $x The value of the x dimension.
     *
     * @return PointValue
     */
    public function x($x)
    {
        $this->x = $x;

        return $this;
    }

    /**
     * Sets the y dimension of the point.
     *
     * @param int $y The value of the y dimension.
     *
     * @return PointValue
     */
    public function y($y)
    {
        $this->y = $y;

        return $this;
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        return "{$this->x}:{$this->y}";
    }

    /**
     * Serializes to json.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'x' => $this->x,
            'y' => $this->y
        ];
    }
}
