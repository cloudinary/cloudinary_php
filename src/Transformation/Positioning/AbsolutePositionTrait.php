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
 * Trait AbsolutePositionTrait
 *
 * @api
 */
trait AbsolutePositionTrait
{
    use PointTrait;

    /**
     * Sets the absolute position.
     *
     * @param int|float|string $x The x dimension.
     * @param int|float|string $y The y dimension.
     *
     * @return $this
     */
    public function position($x = null, $y = null)
    {
        return $this->point($x, $y);
    }
}
