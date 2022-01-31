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
 * Trait ImageFormatTrait
 *
 * @api
 */
trait ImageFormatTrait
{
    /**
     * Image format jpg.
     *
     * @return static
     */
    public static function jpg()
    {
        return static::createFormat(Format::JPG);
    }

    /**
     * Image format jpc.
     *
     * @return static
     */
    public static function jpc()
    {
        return static::createFormat(Format::JPC);
    }

    /**
     * Image format jp2.
     *
     * @return static
     */
    public static function jp2()
    {
        return static::createFormat(Format::JP2);
    }

    /**
     * Image format wdp.
     *
     * @return static
     */
    public static function wdp()
    {
        return static::createFormat(Format::WDP);
    }

    /**
     * Image format png.
     *
     * @return static
     */
    public static function png()
    {
        return static::createFormat(Format::PNG);
    }

    /**
     * Image format gif.
     *
     * @return static
     */
    public static function gif()
    {
        return static::createFormat(Format::GIF);
    }

    /**
     * Image format webp.
     *
     * @return static
     */
    public static function webp()
    {
        return static::createFormat(Format::WEBP);
    }

    /**
     * Image format bmp.
     *
     * @return static
     */
    public static function bmp()
    {
        return static::createFormat(Format::BMP);
    }

    /**
     * Image format tiff.
     *
     * @return static
     */
    public static function tiff()
    {
        return static::createFormat(Format::TIFF);
    }

    /**
     * Image format ico.
     *
     * @return static
     */
    public static function ico()
    {
        return static::createFormat(Format::ICO);
    }

    /**
     * Image format pdf.
     *
     * @return static
     */
    public static function pdf()
    {
        return static::createFormat(Format::PDF);
    }

    /**
     * Image format eps.
     *
     * @return static
     */
    public static function eps()
    {
        return static::createFormat(Format::EPS);
    }

    /**
     * Image format psd.
     *
     * @return static
     */
    public static function psd()
    {
        return static::createFormat(Format::PSD);
    }

    /**
     * Image format svg.
     *
     * @return static
     */
    public static function svg()
    {
        return static::createFormat(Format::SVG);
    }

    /**
     * Image format ai.
     *
     * @return static
     */
    public static function ai()
    {
        return static::createFormat(Format::AI);
    }

    /**
     * Image format djvu.
     *
     * @return static
     */
    public static function djvu()
    {
        return static::createFormat(Format::DJVU);
    }

    /**
     * Image format avif.
     *
     * @return static
     */
    public static function avif()
    {
        return static::createFormat(Format::AVIF);
    }

    /**
     * Image format flif.
     *
     * @return static
     */
    public static function flif()
    {
        return static::createFormat(Format::FLIF);
    }

    /**
     * Image format glb.
     *
     * @return static
     */
    public static function glb()
    {
        return static::createFormat(Format::GLB);
    }

    /**
     * Image format usdz.
     *
     * @return static
     */
    public static function usdz()
    {
        return static::createFormat(Format::USDZ);
    }
}
