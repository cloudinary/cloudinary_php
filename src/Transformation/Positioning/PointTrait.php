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

use Cloudinary\ClassUtils;

/**
 * Trait PointTrait
 *
 * @api
 */
trait PointTrait
{
    /**
     * Sets the x dimension of the point.
     *
     * @param int|float|string $x The value of the x dimension.
     *
     * @return $this
     */
    public function x($x)
    {
        return $this->setPointValue(ClassUtils::verifyInstance($x, X::class));
    }

    /**
     * Sets the y dimension of the point.
     *
     * @param int|float|string $y The value of the y dimension.
     *
     * @return $this
     */
    public function y($y)
    {
        return $this->setPointValue(ClassUtils::verifyInstance($y, Y::class));
    }

    /**
     * Sets the x and y dimensions of the point.
     *
     * @param int|float|string $x The value of the x dimension.
     * @param int|float|string $y The value of the y dimension.
     *
     * @return static
     */
    public function point($x = null, $y = null)
    {
        return $this->x($x)->y($y);
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
    abstract public function setPointValue($value);
}
