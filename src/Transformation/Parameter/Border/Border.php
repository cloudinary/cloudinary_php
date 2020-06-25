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

use Cloudinary\Transformation\Parameter\BaseParameter;
use Cloudinary\Transformation\Parameter\Value\ColorValueTrait;

/**
 * Adds a solid border around an image or video.
 *
 * **Learn more**:
 * <a href="https://cloudinary.com/documentation/image_transformations#adding_image_borders" target="_blank">
 * Adding image borders</a>
 *
 * @property BorderValue $value
 *
 * @api
 */
class Border extends BaseParameter
{
    const VALUE_CLASS = BorderValue::class;

    /**
     * @var string $key Serialization key.
     */
    protected static $key = 'bo';

    use BorderStyleTrait;

    /**
     * Sets the width of the border.
     *
     * @param int $width The width in pixels.
     *
     * @return $this
     */
    public function width($width)
    {
        $this->value->width($width);

        return $this;
    }

    /**
     * Sets the style of the border.
     *
     * @param string $style The style of the border.  Currently only "solid" is supported.
     *
     * @return $this
     */
    public function style($style)
    {
        $this->value->style($style);

        return $this;
    }

    /**
     * Sets the color of the border.
     *
     * @param string $color The color of the border.
     *
     * @return $this
     */
    public function color($color)
    {
        $this->value->color($color);

        return $this;
    }
}
