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
 * Trait OffsetTrait
 *
 * @api
 */
trait OffsetTrait
{
    /**
     * Sets the x offset.
     *
     * @param int|float|string $x The x offset.
     *
     * @return $this
     */
    public function offsetX($x)
    {
        return $this->setOffsetValue(ClassUtils::verifyInstance($x, X::class));
    }

    /**
     * Sets the y offset.
     *
     * @param int|float|string $y The y offset.
     *
     * @return $this
     */
    public function offsetY($y)
    {
        return $this->setOffsetValue(ClassUtils::verifyInstance($y, Y::class));
    }

    /**
     * Sets the x and y offset.
     *
     * @param int|float|string $x The x offset.
     * @param int|float|string $y The y offset.
     *
     * @return static
     */
    public function offset($x = null, $y = null)
    {
        return $this->offsetX($x)->offsetY($y);
    }

    /**
     * @internal
     *
     * @param $value
     *
     * @return static
     */
    abstract public function setOffsetValue($value);
}
