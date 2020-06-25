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
 * Defines the custom colors to use when resizing using content-aware padding.
 *
 * **Learn more**:
 * <a href=https://cloudinary.com/documentation/image_transformations#content_aware_padding
 * target="_blank">Content-aware padding with custom color palette</a>
 *
 * @api
 */
class Palette extends BaseComponent
{
    /**
     * @var array $colors Palette colors.
     */
    protected $colors = [];

    /**
     * Palette constructor.
     *
     * @param array $colors
     */
    public function __construct(array $colors = [])
    {
        parent::__construct();

        $this->colors($colors);
    }

    /**
     * Sets colors.
     *
     * @param array $colors The colors. Can be RGB, HEX, named color, etc
     */
    public function colors(array $colors)
    {
        $this->colors = array_merge($this->colors, $colors);
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        return ! empty($this->colors) ? implode('_', ['palette'] + $this->colors) : '';
    }

    /**
     * Serializes to json.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}
