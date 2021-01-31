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
 * Trait ImageTransformationFlagTrait
 *
 * @api
 */
trait ImageTransformationFlagTrait
{
    /**
     * Instruct Cloudinary to run a sanitizer on the image (relevant only for the SVG format).
     *
     * @return static
     */
    public function sanitize()
    {
        return $this->addFlag(Flag::sanitize());
    }
}
