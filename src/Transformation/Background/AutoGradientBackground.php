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
 * Automatically sets the background color when resizing with padding.
 *
 * **Learn more**:
 * <a href="https://cloudinary.com/documentation/image_transformations#content_aware_padding" target="_blank">
 * Content-aware padding</a>
 *
 * @api
 */
class AutoGradientBackground extends AutoBackground
{
    use AutoGradientBackgroundTrait;

    /**
     * Base the gradient fade effect on the predominant colors in the border pixels of the image.
     */
    const BORDER_GRADIENT = 'border_gradient';

    /**
     * Base the gradient fade effect on the predominant colors in the image.
     */
    const PREDOMINANT_GRADIENT = 'predominant_gradient';


    const GRADIENT_COLORS = 'gradient_colors';
    const DIRECTION       = 'direction';

    protected $valueOrder = [0, self::MODE, self::GRADIENT_COLORS, self::DIRECTION, self::PALETTE];

    /**
     * AutoGradientBackground constructor.
     *
     * @param string     $mode              The auto background mode.
     * @param int|string $gradientColors    The number of gradient colors to select. Possible values: 2 or 4. Default: 2
     * @param null       $gradientDirection The direction. Use the constants defined in the GradientDirection class.
     */
    public function __construct($mode, $gradientColors = null, $gradientDirection = null)
    {
        parent::__construct($mode);

        $this->gradientColors($gradientColors);
        $this->gradientDirection($gradientDirection);
    }

    /**
     * Sets the number of gradient colors to select.
     *
     * @param int|string $gradientColors Possible values: 2 or 4. Default: 2
     *
     * @return $this
     */
    public function gradientColors($gradientColors)
    {
        $this->value->setSimpleValue(self::GRADIENT_COLORS, $gradientColors);

        return $this;
    }

    /**
     * Sets the the direction to blend the colors together.
     *
     * @param string $direction The direction. Use the constants defined in the GradientDirection class.
     *
     * @return $this
     *
     * @see GradientDirection
     */
    public function gradientDirection($direction)
    {
        $this->value->setSimpleValue(self::DIRECTION, $direction);

        return $this;
    }
}
