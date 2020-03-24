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

use Cloudinary\Transformation\Parameter\Dimensions\DimensionsTrait;

/**
 * Trait RegionTrait
 */
trait RegionTrait
{
    use AbsolutePositionTrait;
    use DimensionsTrait;

    /**
     * Sets the region.
     *
     * @param Region $region The region.
     *
     * @return $this
     */
    public function region(Region $region)
    {
        return $this->addParameter($region);
    }
}
