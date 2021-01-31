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
 * Trait ZoomTrait
 */
trait ZoomTrait
{
    /**
     * Controls how much of the original image surrounding the face to keep when using either the 'crop' or 'thumb'
     * cropping modes with face detection.
     *
     * @param float $zoom The zoom factor. (Default: 1.0)
     *
     * @return static
     */
    public function zoom($zoom)
    {
        return $this->addQualifier(ClassUtils::verifyInstance($zoom, Zoom::class));
    }
}
