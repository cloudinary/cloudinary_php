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
 * Trait ImagePixelEffectTrait
 *
 * @api
 */
trait ImagePixelEffectTrait
{
    /**
     * Blurs all detected faces in the image.
     *
     * @param int $strength The strength of the blur. (Range: 1 to 2000, Server default: 500)
     *
     * @return EffectAction
     */
    public static function blurFaces($strength = null)
    {
        return EffectAction::limited(PixelEffect::BLUR_FACES, EffectRange::PIXEL, $strength);
    }

    /**
     * Applies a blurring filter to the region of the image specified by x, y, width and height.
     *
     * @param int $strength The strength of the blur. (Range: 1 to 2000, Server default: 100).
     * @param int $x        The x position in pixels.
     * @param int $y        The y position in pixels.
     * @param int $width    The width of the region in pixels.
     * @param int $height   The height of the region in pixels.
     *
     * @return RegionEffectAction
     */
    public static function blurRegion($strength = null, $x = null, $y = null, $width = null, $height = null)
    {
        return new RegionEffectAction(
            PixelEffect::BLUR_REGION,
            EffectRange::PIXEL,
            $strength,
            new Region($x, $y, $width, $height)
        );
    }

    /**
     * Applies a pixelation effect to the image.
     *
     * @param int $squareSize The width of each pixelation square in pixels.  (Range: 1 to 200, Server default: 5)
     *
     * @return EffectAction
     */
    public static function pixelate($squareSize = null)
    {
        return EffectAction::limited(PixelEffect::PIXELATE, EffectRange::PIXEL_REGION, $squareSize);
    }

    /**
     * Pixelates all detected faces in the image.
     *
     * @param int $squareSize The width of each pixelation square in pixels.  (Range: 1 to 200, Server default: 5)
     *
     * @return EffectAction
     */
    public static function pixelateFaces($squareSize = null)
    {
        return EffectAction::limited(PixelEffect::PIXELATE_FACES, EffectRange::PIXEL_REGION, $squareSize);
    }

    /**
     * Pixelates the region of the image specified by x, y, width and height.
     *
     * @param int $squareSize The width of each pixelation square in pixels. (Range: 1 to 200, Server default: 5)
     * @param int $x          The x position in pixels.
     * @param int $y          The y position in pixels.
     * @param int $width      The width of the region in pixels.
     * @param int $height     The height of the region in pixels.
     *
     * @return RegionEffectAction
     */
    public static function pixelateRegion($squareSize = null, $x = null, $y = null, $width = null, $height = null)
    {
        return new RegionEffectAction(
            PixelEffect::PIXELATE_REGION,
            EffectRange::PIXEL_REGION,
            $squareSize,
            new Region($x, $y, $width, $height)
        );
    }

    /**
     * Makes the background of the image transparent (or solid white for formats that do not support transparency).
     * The background is determined as all pixels that resemble the pixels on the edges of the image.
     *
     * @param int $tolerance The tolerance used to accommodate variance in the background color.
     *                       (Range: 0 to 100, Server default: 10)
     *
     * @return EffectAction
     */
    public static function makeTransparent($tolerance = null)
    {
        return EffectAction::limited(PixelEffect::MAKE_TRANSPARENT, EffectRange::PERCENT, $tolerance);
    }

    /**
     * Applies an ordered dither filter to the image.
     *
     * Use the constants defined in \Cloudinary\Transformation\OrderedDither for $level.
     *
     * @param int $level The level of ordered dither.  Use the constants defined in the OrderedDither class.
     *
     * @return OrderedDither
     *
     * @see \Cloudinary\Transformation\OrderedDither
     *
     */
    public static function orderedDither($level = null)
    {
        return new OrderedDither($level);
    }

    /**
     * Applies a gradient fade effect from the top edge of the image.
     *
     * You can specify other edges using the x and y methods of the \Cloudinary\Transformation\GradientFade class.
     *
     * @param int    $strength The strength of the fade effect. (Range: 0 to 100, Server default: 20)
     * @param string $mode     The mode of gradient fade: GradientFade::SYMMETRIC or GradientFade::SYMMETRIC_PAD.
     *
     * @return GradientFade
     *
     * @see \Cloudinary\Transformation\GradientFade
     *
     */
    public static function gradientFade($strength = null, $mode = null)
    {
        return new GradientFade($strength, $mode);
    }
}
