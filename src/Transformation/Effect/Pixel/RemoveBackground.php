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
use Cloudinary\StringUtils;
use Cloudinary\Transformation\Argument\Color;
use Cloudinary\Transformation\Argument\ColorValue;

/**
 * Class RemoveBackground
 *
 * Makes the background of an image transparent (or solid white for JPGs).
 *
 * Use when the background is a uniform color.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/transformation_reference#e_bgremoval" target="_blank">
 * Background removal</a>
 *
 * @api
 */
class RemoveBackground extends EffectAction
{
    const SCREEN          = 'screen';
    const COLOR_TO_REMOVE = 'color_to_remove';

    /**
     * RemoveBackground constructor.
     *
     * @param bool         $screen        When true, provides better results for images with near perfect green/blue
     *                                    background.
     * @param string|Color $colorToRemove The background color as an RGB/A hex code. Overrides the algorithm's choice of
     *                                    background color.
     *                                    Default: The algorithm's choice - often the edge color of the image.
     */
    public function __construct($screen = false, $colorToRemove = null)
    {
        parent::__construct(new EffectQualifier(PixelEffect::REMOVE_BACKGROUND));

        $this->getMainQualifier()->getValue()->setArgumentOrder([0, self::SCREEN, self::COLOR_TO_REMOVE]);

        $this->screen($screen);
        $this->colorToRemove($colorToRemove);
    }

    /**
     * Provides better results for images with near perfect green/blue background.
     *
     * @param bool $screen Whether to use "screen" mode.
     *
     * @return RemoveBackground
     */
    public function screen($screen = true)
    {
        if ($screen) {
            $this->getMainQualifier()->getValue()->setSimpleValue(self::SCREEN, self::SCREEN);
        }

        return $this;
    }

    /**
     * Overrides the algorithm's choice of background color.
     *
     * @param string|Color $colorToRemove The background color as an RGB/A hex code.
     *
     * @return RemoveBackground
     */
    public function colorToRemove($colorToRemove)
    {
        if ($colorToRemove) {
            // dirty hack to omit rgb: from hex colors
            $colorToRemove = StringUtils::truncatePrefix((string)$colorToRemove, '#');
            $this->getMainQualifier()->getValue()
                 ->setSimpleValue(self::COLOR_TO_REMOVE, ClassUtils::verifyInstance($colorToRemove, ColorValue::class));
        }

        return $this;
    }
}
