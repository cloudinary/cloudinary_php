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
use Cloudinary\Transformation\Argument\PointValue;

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
     * See the Image Transformations guide for examples of each of the filters.
     *
     * @param string $filter The filter to apply. Use the constants defined in the ArtisticFilter class.
     *
     * @return mixed
     *
     * @see \Cloudinary\Transformation\ArtisticFilter
     * @see https://cloudinary.com/documentation/image_transformations#available_filters
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
     *
     * @see \Cloudinary\Transformation\Cartoonify
     */
    public static function cartoonify($lineStrength = null, $colorReduction = null)
    {
        return new Cartoonify($lineStrength, $colorReduction);
    }

    /**
     * Transfers the style of a source artwork to a target photograph using the Neural Artwork Style Transfer
     * add-on.
     *
     * For details, see the Neural Artwork Style Transfer add-on documentation.
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
     *
     * @see https://cloudinary.com/documentation/neural_artwork_style_transfer_addon
     */
    public static function styleTransfer($source, $strength = null, $preserveColor = null)
    {
        return new StyleTransfer($source, $strength, $preserveColor);
    }

    /**
     * Applies an oil painting effect to the image.
     *
     * @param int $strength The strength of the effect. (Range: 0 to 100, Server default: 30)
     *
     * @return StrengthEffectAction
     */
    public static function oilPaint($strength = null)
    {
        return EffectAction::withStrength(MiscEffect::OIL_PAINT, EffectRange::PERCENT, $strength);
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
     * For details, see the Advanced Facial Attribute Detection add-on documentation.
     *
     * @return EffectAction
     *
     * @see https://cloudinary.com/documentation/advanced_facial_attributes_detection_addon
     */
    public static function advancedRedEye()
    {
        return EffectAction::named(MiscEffect::ADVANCED_RED_EYE);
    }

    /**
     * Vectorizes the image.
     *
     * Use the methods in \Cloudinary\Transformation\Vectorize to control different aspects of the vectorize effect.
     *
     * Notes:
     * * To deliver the image as a vector image, make sure to change the format (or URL extension) to a vector format,
     *  such as SVG. However, you can also deliver in a raster format if you just want to get the 'vectorized'
     *  graphic effect.
     * * Large images are scaled down to 1000 pixels in the largest dimension before vectorization.
     *
     * @param int   $colors    The number of colors. (Range: 2 to 30, Server default: 10)
     * @param float $detail    The level of detail. Specify either a percentage of the original image (Range: 0.0 to
     *                         1.0) or an absolute number of pixels (Range: 0 to 1000). (Server default: 300)
     * @param float $despeckle The size of speckles to suppress. Specify either a percentage of the original image
     *                         (Range: 0.0 to 1.0) or an absolute number of pixels (Range: 0 to 100, Server default: 2)
     * @param int   $corners   The corner threshold.  Specify 100 for no smoothing (polygon corners), 0 for completely
     *                         smooth corners. (Range: 0 to 100, Default: 25)
     * @param int   $paths     The optimization value. Specify 100 for least optimization and the largest file.
     *                         (Range: 0 to 100, Server default: 100).
     *
     * @return Vectorize
     *
     * @see \Cloudinary\Transformation\Vectorize
     */
    public static function vectorize($colors = null, $detail = null, $despeckle = null, $corners = null, $paths = null)
    {
        return new Vectorize($colors, $detail, $despeckle, $corners, $paths);
    }

    /**
     * Adds an outline to a transparent image.
     *
     * For examples, see the Image Transformations guide.
     *
     * @param string $mode      The type of outline effect. Use the constants defined in the Outline class.
     *                          (Default: OutlineMode::INNER and OutlineMode::OUTER).
     * @param int    $width     The thickness of the outline in pixels. (Range: 1 to 100, Server default: 5)
     * @param int    $blurLevel The level of blur of the outline. (Range: 0 to 2000, Server default: 0)
     *
     * @return Outline
     *
     * @see \Cloudinary\Transformation\Outline
     * @see \Cloudinary\Transformation\OutlineMode
     * @see https://cloudinary.com/documentation/image_transformations#outline_effects
     *
     */
    public static function outline($mode = null, $width = null, $blurLevel = null)
    {
        return new Outline($mode, $width, $blurLevel);
    }

    /**
     * Adds a shadow to the image.
     *
     * The shadow is offset by the x and y values specified in the $position qualifier.
     *
     * @param int        $strength The strength of the shadow. (Range: 0 to 100, Server default: 40)
     * @param PointValue $position The position of the shadow. (Server default: bottom right)
     * @param string     $color    The color of the shadow (Server default: gray)
     *
     * @return Shadow
     * @see \Cloudinary\Transformation\Shadow
     *
     */
    public static function shadow($strength = null, $position = null, $color = null)
    {
        return new Shadow($strength, $position, $color);
    }
}
