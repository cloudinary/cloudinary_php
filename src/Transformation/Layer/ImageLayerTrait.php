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
 * Trait ImageLayerTrait
 *
 * @api
 */
trait ImageLayerTrait
{
    /**
     * Adds another image layer.
     *
     * @param string $publicId The public ID of the new image layer.
     *
     * @return static|ImageLayer
     */
    public static function image($publicId)
    {
        return static::createWithLayer(ClassUtils::verifyInstance($publicId, ImageLayer::class));
    }

    /**
     * Applies a look-up table (LUT) file to the image.
     *
     * @param string $lutId The public ID of the LUT file.
     *
     * @return static|LutLayer
     */
    public static function lut($lutId)
    {
        return static::createWithLayer(ClassUtils::verifyInstance($lutId, LutLayer::class));
    }

    /**
     * Adds a text layer.
     *
     * @param string $text The text to display.
     * @param string $style The text style.
     * @param string $color The text color.
     *
     * @return static|TextLayer
     *
     * @see \Cloudinary\Transformation\TextLayer
     */
    public static function text($text = null, $style = null, $color = null)
    {
        return static::createWithLayer(new TextLayer($text, $style, $color));
    }

    /**
     * Named constructor.
     *
     * @param BaseLayer|string $layer The layer source.
     *
     * @return static
     */
    protected static function createWithLayer($layer)
    {
        return $layer;
    }
}
