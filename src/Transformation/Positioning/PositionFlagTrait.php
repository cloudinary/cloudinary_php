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
 * Trait PositionFlagTrait
 *
 * @package Cloudinary\Transformation
 */
trait PositionFlagTrait
{
    /**
     * Tiles the added overlay over the entire image.
     *
     * @return static
     */
    public function tiled()
    {
        return $this->setFlag(Flag::tiled());
    }

    /**
     * Prevents Cloudinary from extending the image canvas beyond the original dimensions when overlaying text and
     * other images.
     *
     * @param bool $allowOverflow Indicates whether to allow overflow.
     *
     * @return static
     *
     * @see Flag::noOverflow
     */
    public function allowOverflow($allowOverflow = false)
    {
        $this->setFlag(Flag::noOverflow(), !$allowOverflow);

        return $this;
    }
}
