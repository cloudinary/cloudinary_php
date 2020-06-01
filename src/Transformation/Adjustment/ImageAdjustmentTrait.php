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
 * Trait ImageAdjustmentTrait
 *
 * @api
 */
trait ImageAdjustmentTrait
{
    /**
     * Adjusts the brightness and blends the result with the original image.
     *
     * @param int $blendPercentage How much to blend the adjusted brightness, where 0 means only use the original
     *                             and 100 means only use the adjusted brightness result.
     *                             (Range: 0 to 100, Server default: 100)
     *
     * @return EffectAction
     */
    public static function autoBrightness($blendPercentage = null)
    {
        return EffectAction::limited(Adjust::AUTO_BRIGHTNESS, EffectRange::PERCENT, $blendPercentage);
    }

    /**
     * Adjusts the color balance and blends the result with the original image.
     *
     * @param int $blendPercentage How much to blend the adjusted color result, where 0 means only use the original
     *                             and 100 means only use the adjusted color result.
     *                             (Range: 0 to 100, Server default: 100)
     *
     * @return EffectAction
     */
    public static function autoColor($blendPercentage = null)
    {
        return EffectAction::limited(Adjust::AUTO_COLOR, EffectRange::PERCENT, $blendPercentage);
    }

    /**
     * Adjusts the contrast and blends the result with the original image.
     *
     * @param int $blendPercentage How much to blend the adjusted contrast, where 0 means only use the original
     *                             and 100 means only use the adjusted contrast result.
     *                             (Range: 0 to 100, Server default: 100)
     *
     * @return EffectAction
     */
    public static function autoContrast($blendPercentage = null)
    {
        return EffectAction::limited(Adjust::AUTO_CONTRAST, EffectRange::PERCENT, $blendPercentage);
    }

    /**
     * Adjusts the fill light and blends the result with the original image.
     *
     * @param float $blendPercentage How much to blend the adjusted fill light, where 0 means only use the original
     *                             and 100 means only use the adjusted fill light result.
     *                             (Range: 0 to 100, Server default: 100)
     * @param float $bias            The bias to apply to the fill light effect (Range: -100 to 100, Server default: 0).
     *
     *
     * @return EffectAction
     */
    public static function fillLight($blendPercentage = null, $bias = null)
    {
        return EffectAction::limited(Adjust::FILL_LIGHT, EffectRange::DEFAULT_RANGE, $blendPercentage, $bias);
    }

    /**
     * Adjusts the image colors, contrast and brightness.
     *
     * Use the constants defined in \Cloudinary\Transformation\Improve for $mode.
     *
     * @param int    $blendPercentage How much to blend the improved result with the original image,
     *                                where 0 means only use the original and 100 means only use the improved result.
     *                                (Range: 0 to 100, Server default: 100)
     *
     * @param string $mode            The improve mode. Use the constants defined in the Improve class.
     *
     * @return Improve
     *
     * @see \Cloudinary\Transformation\Improve
     */
    public static function improve($blendPercentage = null, $mode = null)
    {
        return new Improve($blendPercentage, $mode);
    }

    /**
     * Applies a vibrance filter on the image.
     *
     * @param int $strength The strength of the vibrance. (Range: -100 to 100, Server default: 20)
     *
     * @return EffectAction
     */
    public static function vibrance($strength = null)
    {
        return EffectAction::limited(Adjust::VIBRANCE, EffectRange::DEFAULT_RANGE, $strength);
    }

    /**
     * Enhances an image to its best visual quality with the Viesus Automatic Image Enhancement add-on.
     *
     * For details, see the Viesus Automatic Image Enhancement add-on documentation.
     *
     * @param string $mode  The mode of use.  Use the constants defined in the ViesusCorrect class.
     * @param int    $level The level of saturation to apply to skin tones.  It is only applicable when $mode is set to
     *                      ViesusCorrect::SKIN_SATURATION.  A positive value boosts the saturation and a negative value
     *                      reduces the saturation.
     *                      (Range: -100 to 100, Server default: 50)
     *
     * @return ViesusCorrect
     *
     * @see \Cloudinary\Transformation\ViesusCorrect
     * @see https://cloudinary.com/documentation/viesus_automatic_image_enhancement_addon
     */
    public static function viesusCorrect($mode = null, $level = null)
    {
        return new ViesusCorrect($mode, $level);
    }

    /**
     * Adjusts the image's red channel.
     *
     * @param int $level The level of red. (Range: -100 to 100, Server default: 0)
     *
     * @return EffectAction
     */
    public static function red($level = null)
    {
        return EffectAction::limited(Adjust::RED, EffectRange::DEFAULT_RANGE, $level);
    }

    /**
     * Adjusts the image's green channel.
     *
     * @param int $level The level of green. (Range: -100 to 100, Server default: 0)
     *
     * @return EffectAction
     */
    public static function green($level = null)
    {
        return EffectAction::limited(Adjust::GREEN, EffectRange::DEFAULT_RANGE, $level);
    }

    /**
     * Adjusts the image's blue channel.
     *
     * @param int $level The level of blue. (Range: -100 to 100, Server default: 0)
     *
     * @return EffectAction
     */
    public static function blue($level = null)
    {
        return EffectAction::limited(Adjust::BLUE, EffectRange::DEFAULT_RANGE, $level);
    }

    /**
     * Adjusts image brightness modulation in HSB to prevent artifacts in some images.
     *
     * @param int $level The level of modulation. (Range: -99 to 100, Server default: 80)
     *
     * @return EffectAction
     */
    public static function brightnessHSB($level = null)
    {
        return EffectAction::limited(Adjust::BRIGHTNESS_HSB, EffectRange::BRIGHTNESS, $level);
    }

    /**
     * Adjusts the image's hue.
     *
     * @param int $level The level of hue. (Range: -100 to 100, Server default: 80)
     *
     * @return EffectAction
     */
    public static function hue($level = null)
    {
        return EffectAction::limited(Adjust::HUE, EffectRange::DEFAULT_RANGE, $level);
    }

    /**
     * Blends the image with one or more tint colors at the intensity specified.
     *
     * Optional - equalize colors before tinting, specify gradient blend positioning per color.
     *
     * @param array $params Syntax: [equalize]:[amount]:[color1]:[color1_position]:[color2]:[color2_position]:...
     *                      [color10]:[color10_position]
     *
     * @return EffectAction
     */
    public static function tint(...$params)
    {
        return EffectAction::named(Adjust::TINT, ...$params);
    }

    /**
     * Maps an input color and those similar to the input color to corresponding shades of a specified output color,
     * taking luminosity and chroma into account, in order to recolor an object in a natural way.
     *
     * More highly saturated input colors usually give the best results. It is recommended to avoid input colors
     * approaching white, black, or gray.
     *
     * @param string $to        The HTML name or RGB/A hex code of the target output color.
     * @param int    $tolerance The tolerance threshold (a radius in the LAB color space) from the input color,
     *                          representing the span of colors that should be replaced with a correspondingly adjusted
     *                          version of the target output color. Larger values result in replacing more colors
     *                          within the image. The more saturated the original input color, the more a change in
     *                          value will impact the result (Server default: 50).
     * @param string $from      The HTML name or RGB/A hex code of the base input color to map
     *                          (Server default: the most prominent high-saturation color in the image).
     *
     * @return ReplaceColor
     *
     * @see \Cloudinary\Transformation\ReplaceColor
     */
    public static function replaceColor($to, $tolerance = null, $from = null)
    {
        return new ReplaceColor($to, $tolerance, $from);
    }

    /**
     * Converts the colors of every pixel in an image based on the supplied color matrix, in which the value of each
     * color channel is calculated based on the values from all other channels (e.g. a 3x3 matrix for RGB, a 4x4 matrix
     * for RGBA or CMYK, etc).
     *
     * For every pixel in the image, take each color channel and adjust its value by the
     * specified values of the matrix to get a new value.
     *
     * @param array $params The matrix of colors.
     *
     * @return EffectAction
     */
    public static function recolor(...$params) // FIXME: add matrix here
    {
        return EffectAction::named(Adjust::RECOLOR, ...$params);
    }

    /**
     * Applies a sharpening filter to the image.
     *
     * @param int $strength The strength of the filter. (Range: 1 to 2000, Server default: 100)
     *
     * @return EffectAction
     */
    public static function sharpen($strength = null)
    {
        return EffectAction::limited(Adjust::SHARPEN, EffectRange::PIXEL, $strength);
    }

    /**
     * Applies an unsharp mask filter to the image.
     *
     * @param int $strength The strength of the filter. (Range: 1 to 2000, Server default: 100)
     *
     * @return EffectAction
     */
    public static function unsharpMask($strength = null)
    {
        return EffectAction::limited(Adjust::UNSHARP_MASK, EffectRange::PIXEL, $strength);
    }

    /**
     * Causes all semi-transparent pixels in an image to be either fully transparent or fully opaque.
     *
     * Each pixel with an opacity lower than the specified threshold value is set to an opacity of 0%. Each pixel with
     * an opacity greater than the specified threshold is set to an opacity of 100%. For example, setting
     * opacity_threshold:0 makes all pixels non-transparent, while opacity_threshold:100 makes all semi-transparent
     * pixels fully transparent. Note: This effect can be a useful solution when PhotoShop PSD files are delivered in a
     * format supporting partial transparency, such as PNG, and the results without this effect are not as expected.
     *
     * @param int $level The level of the threshold. (Range: 1 to 100, Server default: 50)
     *
     * @return EffectAction
     */
    public static function opacityThreshold($level = null)
    {
        return EffectAction::limited(Adjust::OPACITY_THRESHOLD, EffectRange::POSITIVE_PERCENT, $level);
    }

    /**
     * Adjusts the opacity of the image and makes it semi-transparent.
     *
     * @param float $level The level of opacity. 100 means opaque, while 0 is completely transparent.
     *                   (Range: 0 to 100)
     *
     * @return Opacity
     *
     * @see \Cloudinary\Transformation\Opacity
     */
    public static function opacity($level = null)
    {
        return new Opacity($level);
    }
}
