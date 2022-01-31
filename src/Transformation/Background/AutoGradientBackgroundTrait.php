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
 * Trait AutoGradientBackgroundTrait
 *
 * @api
 */
trait AutoGradientBackgroundTrait
{
    /**
     * Base the gradient fade effect on the predominant colors in the border pixels of the image.
     *
     * @param int|string $gradientColors    The number of gradient colors to select. Possible values: 2 or 4. Default: 2
     * @param string     $gradientDirection The direction. Use the constants defined in the GradientDirection class.
     *
     * @return AutoGradientBackground
     */
    public static function borderGradient($gradientColors = null, $gradientDirection = null)
    {
        return new AutoGradientBackground(AutoGradientBackground::BORDER_GRADIENT, $gradientColors, $gradientDirection);
    }

    /**
     * Base the gradient fade effect on the predominant colors in the image.
     *
     * @param int|string $gradientColors    The number of gradient colors to select. Possible values: 2 or 4. Default: 2
     * @param string     $gradientDirection The direction. Use the constants defined in the GradientDirection class.
     *
     * @return AutoGradientBackground
     */
    public static function predominantGradient($gradientColors = null, $gradientDirection = null)
    {
        return new AutoGradientBackground(
            AutoGradientBackground::PREDOMINANT_GRADIENT,
            $gradientColors,
            $gradientDirection
        );
    }
}
