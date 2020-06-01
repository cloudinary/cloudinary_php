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

use Cloudinary\Transformation\Argument\ColorTrait;
use Cloudinary\Transformation\Parameter\BaseParameter;
use Cloudinary\Transformation\Parameter\Value\ColorValueTrait;

/**
 * Class Border
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
    use ColorTrait;
    use ColorValueTrait;

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
}
