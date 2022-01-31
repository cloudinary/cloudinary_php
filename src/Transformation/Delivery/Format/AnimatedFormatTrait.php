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
 * Trait AnimatedFormatTrait
 *
 * @api
 */
trait AnimatedFormatTrait
{
    /**
     * Animated image format webp.
     *
     * @return static
     */
    public static function webp()
    {
        return static::createFormat(Format::WEBP)->setFlag(Flag::animatedWebP());
    }

    /**
     * Animated image format gif.
     *
     * @return static
     */
    public static function gif()
    {
        return static::createFormat(Format::GIF);
    }

    /**
     * Animated image format png.
     *
     * @return static
     */
    public static function png()
    {
        return static::createFormat(Format::PNG)->setFlag(Flag::animatedPng());
    }

    /**
     * Animated image auto format.
     *
     * @return static
     */
    public static function auto()
    {
        return static::createFormat(Format::AUTO);
    }

    /**
     * Named constructor.
     *
     * @param string|array $format The format.
     *
     * @return static
     *
     * @internal
     */
    protected static function createFormat(...$format)
    {
        return new static(...$format);
    }


}
