<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Parameter\Value;

use Cloudinary\ClassUtils;
use Cloudinary\Transformation\Argument\ColorValue;

/**
 * Trait ColorValueTrait
 *
 * @api
 */
trait ColorValueTrait
{
    /**
     * Sets the color.
     *
     * @param string $color The color. Can be RGB, HEX, named color, etc.
     *
     * @return $this
     */
    public function color($color)
    {
        $this->setParamValue(ClassUtils::verifyInstance($color, ColorValue::class));

        return $this;
    }
}
