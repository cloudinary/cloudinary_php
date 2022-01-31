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
use Cloudinary\Transformation\Qualifier\BaseQualifier;
use Cloudinary\Transformation\Qualifier\Value\ColorValueTrait;

/**
 * Defines the background color to use instead of transparent background areas or when resizing with padding.
 *
 * **Learn more**:
 * <a href="https://cloudinary.com/documentation/image_transformations#setting_background_color" target="_blank">
 * Setting background for images</a> |
 * <a href="https://cloudinary.com/documentation/video_manipulation_and_delivery#background_color" target="_blank">
 * Setting background for videos</a>
 *
 * @api
 */
class Background extends BaseQualifier
{
    use AutoBackgroundTrait;
    use AutoGradientBackgroundTrait;
    use ColorValueTrait;

    /**
     * Background constructor.
     *
     * @param $color
     */
    public function __construct($color)
    {
        parent::__construct(ClassUtils::verifyInstance($color, ColorValue::class));
    }

    /**
     * Sets the background color.
     *
     * @param string $color The color. Can be RGB, HEX, named color, etc.
     *
     * @return Background
     *
     */
    public static function color($color)
    {
        return new self($color);
    }

    /**
     * Applies blurred background (Relevant only for videos).
     *
     * @param int $intensity  The intensity of the blur.
     * @param int $brightness The brightness of the background.
     *
     * @return BlurredBackground
     *
     */
    public static function blurred($intensity = null, $brightness = null)
    {
        return new BlurredBackground($intensity, $brightness);
    }

    /**
     * Applies background color automatically.
     *
     * @param string $autoBackground The type of the background color. See AutoBackground class.
     *
     * @return AutoBackground
     *
     * @see AutoBackground
     */
    public static function auto($autoBackground = null)
    {
        return ClassUtils::forceInstance($autoBackground, AutoBackground::class);
    }
}
