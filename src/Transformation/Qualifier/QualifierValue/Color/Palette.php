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
class Palette extends QualifierMultiValue
{
    const VALUE_DELIMITER = '_';
    const MIN_ARGUMENTS   = 1;

    /**
     * Adds colors to the palette.
     *
     * @param array $colors The colors. Can be RGB, HEX, named color, etc
     */
    public function colors(...$colors)
    {
        $this->addValues(...$colors);
    }
}
