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
 * Interface RotationDegreeInterface
 */
interface RotationDegreeInterface
{
    /**
     * Creates the instance.
     *
     * @param int|array $degree Given degrees or mode.
     *
     * @return self
     *
     * @internal
     */
    public static function createWithDegree(...$degree);
}
