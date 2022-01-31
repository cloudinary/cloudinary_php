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
 * Trims pixels according to the transparency levels of a given overlay image.
 *
 * Wherever the overlay image is transparent, the original is shown, and wherever the overlay is opaque, the
 * resulting image is transparent.
 *
 * @package Cloudinary\Transformation
 */
class CutByImage extends ImageOverlay
{
    /**
     * CutByImage constructor.
     *
     * @param string|BaseSource         $source   The public ID of the image overlay.
     * @param Position|AbsolutePosition $position The position of the overlay with respect to the base image.
     */
    public function __construct($source, $position = null)
    {
        parent::__construct($source, $position);

        $this->setFlag(Flag::cutter());
    }
}
