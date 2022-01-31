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
use Cloudinary\Transformation\Argument\ColorValue;

/**
 * Trait BackgroundTrait
 *
 * @api
 */
trait BackgroundColorTrait
{
    /**
     * Sets the color of the background.
     *
     * @param Background|ColorValue|string $color The color of the background to set.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\Background
     */
    public function backgroundColor($color)
    {
        $this->addQualifier(ClassUtils::verifyInstance($color, Background::class));

        return $this;
    }
}
