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
 * Trait ColorToReplaceTrait
 */
trait ColorToReplaceTrait
{
    /**
     * Sets the color to replace.
     *
     * @param string $color The color.
     *
     * @return $this
     */
    public function colorToReplace($color)
    {
        $this->addQualifier(ClassUtils::verifyInstance($color, ColorQualifier::class));

        return $this;
    }
}
