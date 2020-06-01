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
 * Trait LayerFlagBuilderTrait
 *
 * @api
 */
trait LayerFlagBuilderTrait
{
    /**
     * Modifies percentage-based width & height parameters of overlays and underlays (e.g., 1.0) to be relative to the
     * overlaid region
     *
     * @return static
     *
     * @see Flag::regionRelative
     *
     */
    public function regionRelative()
    {
        $this->addFlag(Flag::regionRelative());

        return $this;
    }

    /**
     * Modifies percentage-based width & height parameters of overlays and underlays (e.g., 1.0) to be relative to the
     * containing image instead of the added layer.
     *
     * @return static
     *
     * @see Flag::relative
     *
     */
    public function relative()
    {
        $this->addFlag(Flag::relative());

        return $this;
    }

    /**
     * Prevents Cloudinary from extending the image canvas beyond the original dimensions when overlaying text and
     * other images.
     *
     * @return static
     *
     * @see Flag::noOverflow
     *
     */
    public function noOverflow()
    {
        $this->addFlag(Flag::noOverflow());

        return $this;
    }
}
