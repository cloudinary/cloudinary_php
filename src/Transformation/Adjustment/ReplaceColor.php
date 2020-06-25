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
 * Maps an input color and those similar to the input color to corresponding shades of a specified output color,
 * taking luminosity and chroma into account, in order to recolor objects in your image in a natural way.
 * More highly saturated input colors usually give the best results. It is recommended to avoid input colors
 * approaching white, black, or gray.
 *
 * **Learn more**:
 * <a href=https://cloudinary.com/documentation/image_transformations#replace_color_effect
 * target="_blank">Replace colors example</a>
 *
 * @api
 */
class ReplaceColor extends EffectParam
{
    /**
     * @var array $valueOrder The order of the values.
     */
    protected $valueOrder = [0, 'to', 'tolerance', 'from'];

    /**
     * ReplaceColor constructor.
     *
     * @param      $to
     * @param      $tolerance
     * @param null $from
     */
    public function __construct($to, $tolerance = null, $from = null)
    {
        parent::__construct(Adjust::REPLACE_COLOR);

        $this->to($to);
        $this->tolerance($tolerance);
        $this->from($from);
    }

    /**
     * Sets the target output color.
     *
     * @param string $to The HTML name or RGB/A hex code of the target output color.
     *
     * @return ReplaceColor
     */
    public function to($to)
    {
        $this->value->setSimpleValue('to', ClassUtils::verifyInstance($to, ColorValue::class));

        return $this;
    }

    /**
     * Sets the tolerance threshold.
     *
     * @param int $tolerance    The tolerance threshold (a radius in the LAB color space) from the input color,
     *                          representing the span of colors that should be replaced with a correspondingly adjusted
     *                          version of the target output color. Larger values result in replacing more colors
     *                          within the image. The more saturated the original input color, the more a change in
     *                          value will impact the result.
     *
     * @return ReplaceColor
     */
    public function tolerance($tolerance)
    {
        $this->value->setSimpleValue('tolerance', $tolerance);

        return $this;
    }

    /**
     * Sets the base input color to map.
     *
     * @param string $from The HTML name or RGB/A hex code of the base input color to map
     *
     * @return ReplaceColor
     */
    public function from($from)
    {
        $this->value->setSimpleValue('from', ClassUtils::verifyInstance($from, ColorValue::class));

        return $this;
    }
}
