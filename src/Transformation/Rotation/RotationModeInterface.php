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

use Cloudinary\Transformation\Argument\RotationMode;

/**
 * Interface RotationModeInterface
 */
interface RotationModeInterface
{
    /**
     * Creates the instance.
     *
     * @param string|RotationMode|array $mode Given mode.
     *
     * @return self
     *
     * @internal
     */
    public static function createWithMode(...$mode);
}
