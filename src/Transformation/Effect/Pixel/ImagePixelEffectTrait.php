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

use Cloudinary\Asset\Media;
use Cloudinary\Transformation\Argument\Color;

/**
 * Trait ImagePixelEffectTrait
 *
 * @api
 */
trait ImagePixelEffectTrait
{
    /**
     * Applies a pixelation effect to the image.
     *
     * @param int $squareSize The width of each pixelation square in pixels.  (Range: 1 to 200, Server default: 5)
     *
     * @return Pixelate
     */
    public static function pixelate($squareSize = null)
    {
        return new Pixelate($squareSize);
    }

    /**
     * Makes the background of the image transparent (or solid white for formats that do not support transparency).
     * The background is determined as all pixels that resemble the pixels on the edges of the image.
     *
     * @param int $tolerance The tolerance used to accommodate variance in the background color.
     *                       (Range: 0 to 100, Server default: 10)
     *
     * @return MakeTransparent
     */
    public static function makeTransparent($tolerance = null)
    {
        return new MakeTransparent(
            new ToleranceEffectQualifier(PixelEffect::MAKE_TRANSPARENT, EffectRange::PERCENT, $tolerance)
        );
    }

    /**
     * Makes the background of an image transparent (or solid white for JPGs).
     *
     * Use when the background is a uniform color.
     *
     * @param bool         $screen        When true, provides better results for images with near perfect green/blue
     *                                    background.
     * @param string|Color $colorToRemove The background color as an RGB/A hex code. Overrides the algorithm's choice of
     *                                    background color.
     *                                    Default: The algorithm's choice - often the edge color of the image.
     *
     * @return RemoveBackground
     *
     * @see \Cloudinary\Transformation\RemoveBackground
     */
    public static function removeBackground($screen = false, $colorToRemove = null)
    {
        return new RemoveBackground($screen, $colorToRemove);
    }

    /**
     * Applies an ordered dither filter to the image.
     *
     * Use the constants defined in \Cloudinary\Transformation\OrderedDither for $level.
     *
     * @param int $level The level of ordered dither.  Use the constants defined in the OrderedDither class.
     *
     * @return Dither
     *
     * @see \Cloudinary\Transformation\Dither
     *
     */
    public static function dither($level = null)
    {
        return new Dither($level);
    }

    /**
     * Applies a gradient fade effect from the top edge of the image.
     *
     * You can specify other edges using the x and y methods of the \Cloudinary\Transformation\GradientFade class.
     *
     * @param int    $strength The strength of the fade effect. (Range: 0 to 100, Server default: 20)
     * @param string $type     The type of gradient fade: GradientFade::SYMMETRIC or GradientFade::SYMMETRIC_PAD.
     *
     * @return GradientFade
     *
     * @see \Cloudinary\Transformation\GradientFade
     *
     */
    public static function gradientFade($strength = null, $type = null)
    {
        return new GradientFade($strength, $type);
    }

    /**
     * Trims pixels according to the transparency levels of a specified overlay image.
     *
     * Wherever an overlay image is transparent, the original is shown, and wherever an overlay is opaque, the
     * resulting image is transparent.
     *
     * @param string|Media $source The public ID of the source.
     *
     * @return CutOut
     *
     * @see \Cloudinary\Transformation\CutOut
     *
     */
    public static function cutOut($source = null)
    {
        return new CutOut($source);
    }
}
