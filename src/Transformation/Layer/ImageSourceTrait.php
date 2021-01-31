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
 * Trait ImageSourceTrait
 *
 * @api
 */
trait ImageSourceTrait
{
    /**
     * Adds another image layer.
     *
     * @param string $publicId The public ID of the new image layer.
     *
     * @return static|ImageSource
     */
    public static function image($publicId)
    {
        return static::createWithSource(ClassUtils::verifyInstance($publicId, ImageSource::class));
    }

    /**
     * Adds another image layer.
     *
     * @param string|null $fetchUrl The URL of the asset to fetch.
     *
     * @return static|FetchImageSource
     */
    public static function fetch($fetchUrl)
    {
        return static::createWithSource(ClassUtils::verifyInstance($fetchUrl, FetchImageSource::class));
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
        return static::createWithSource(ClassUtils::verifyInstance($lutId, LutLayer::class));
    }

    /**
     * Adds a text layer.
     *
     * @param string $text The text to display.
     * @param string $style The text style.
     * @param string $color The text color.
     *
     * @return static|TextSource
     *
     * @see \Cloudinary\Transformation\TextSource
     */
    public static function text($text = null, $style = null, $color = null)
    {
        return static::createWithSource(new TextSource($text, $style, $color));
    }

    /**
     * Named constructor.
     *
     * @param BaseSource|string $source The layer source.
     *
     * @return static
     */
    protected static function createWithSource($source)
    {
        return $source;
    }
}
