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

/**
 * Trait MiscEffectTrait
 *
 * @api
 */
trait MiscEffectTrait
{
    /**
     * Applies the selected artistic filter to the image.
     *
     * See the {@see https://cloudinary.com/documentation/image_transformations#available_filters image transformation
     * guide} for examples of each of the filters. Use the constants defined
     * in {@see \Cloudinary\Transformation\ArtisticFilter ArtisticFilter} for $filter.
     *
     * @param string $filter The filter to apply. Use the constants defined in the ArtisticFilter class.
     *
     * @return mixed
     * @see \Cloudinary\Transformation\ArtisticFilter
     *
     */
    public static function artisticFilter($filter)
    {
        return ClassUtils::verifyInstance($filter, ArtisticFilter::class);
    }

    /**
     * Applies a cartoon effect to an image.
     *
     * @param float        $lineStrength   The thickness of the lines. (Range: 0 to 100, Server default: 50)
     * @param float|string $colorReduction The decrease in the number of colors and corresponding saturation boost of
     *                                     the remaining colors. (Range: 0 to 100, Server default: automatically
     *                                     adjusts according to the line_strength value). Higher reduction values
     *                                     result in a less realistic look. Set $colorReduction to
     *                                     Cartoonify::BLACK_WHITE for a black and white cartoon effect.
     *
     * @return Cartoonify
     * @see \Cloudinary\Transformation\Cartoonify
     *
     */
    public static function cartoonify($lineStrength = null, $colorReduction = null)
    {
        return new Cartoonify($lineStrength, $colorReduction);
    }

    /**
     * Transfers the style of a source artwork to a target photograph using the Neural Artwork Style Transfer
     * add-on.
     *
     * See the {@see https://cloudinary.com/documentation/neural_artwork_style_transfer_addon Neural Artwork
     * Style Transfer add-on documentation} for further information.
     *
     * @param string $source        The public ID of the source artwork.
     * @param int    $strength      The strength of the style transfer. Higher numbers result in an output that is more
     *                              highly influenced by the source artwork style. (Range: 0 to 100, Server default:
     *                              100)
     * @param bool   $preserveColor When true, style elements of the source artwork, such as brush style and texture,
     *                              are transferred to the target photo, but the prominent colors from the source
     *                              artwork are not transferred, so the result retains the original colors of the
     *                              target photo.
     *
     * @return EffectAction
     */
    public static function styleTransfer($source, $strength = null, $preserveColor = null)
    {
        return EffectAction::fromEffectParam(new StyleTransfer($strength, $preserveColor))
                           ->addParameter(Layer::image($source)); // TODO: discuss whether we need layer_apply
    }

    /**
     * Applies an oil painting effect to the image.
     *
     * @param int $strength The strength of the effect. (Range: 0 to 100, Server default: 30)
     *
     * @return EffectAction
     */
    public static function oilPaint($strength = null)
    {
        return EffectAction::limited(MiscEffect::OIL_PAINT, EffectRange::PERCENT, $strength);
    }

    /**
     * Removes red eyes in the image.
     *
     * @return EffectAction
     */
    public static function redEye()
    {
        return EffectAction::named(MiscEffect::RED_EYE);
    }

    /**
     * Removes red eyes with the Advanced Facial Attribute Detection add-on.
     *
     * See the {@see https://cloudinary.com/documentation/advanced_facial_attributes_detection_addon Advanced
     * Facial Attribute Detection add-on documentation} for further information.
     *
     * @return EffectAction
     */
    public static function advancedRedEye()
    {
        return EffectAction::named(MiscEffect::ADVANCED_RED_EYE);
    }

    /**
     * Vectorizes the image.
     *
     * Use the methods in {@see \Cloudinary\Transformation\VectorizeTrait VectorizeTrait} to control different
     * aspects of the vectorize effect.
     *
     * Notes:
     * * To deliver the image as a vector image, make sure to change the format (or URL extension) to a vector format,
     *  such as SVG. However, you can also deliver in a raster format if you just want to get the 'vectorized'
     *  graphic effect.
     * * Large images are scaled down to 1000 pixels in the largest dimension before vectorization.
     *
     * @return Vectorize
     * @see \Cloudinary\Transformation\Vectorize
     *
     */
    public static function vectorize()
    {
        return new Vectorize();
    }

    /**
     * Adds an outline to a transparent image.
     *
     * See the {@see https://cloudinary.com/documentation/image_transformations#outline_effects image transformation
     * guide} for examples.  Use the constants defined
     * in {@see \Cloudinary\Transformation\Outline Outline} for $mode.
     *
     * @param string $mode  The type of outline effect. Use the constants defined in the Outline class.
     *                      (Default: Outline::INNER and Outline::OUTER).
     * @param int    $width The thickness of the outline in pixels. (Range: 1 to 100, Server default: 5)
     * @param int    $blur  The level of blur of the outline. (Range: 0 to 2000, Server default: 0)
     *
     * @return Outline
     * @see \Cloudinary\Transformation\Outline
     *
     */
    public static function outline($mode = null, $width = null, $blur = null)
    {
        return new Outline($mode, $width, $blur);
    }
}
