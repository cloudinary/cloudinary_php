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
 * Trait QualityTrait
 *
 * @api
 */
trait QualityTrait
{
    /**
     * Sets the quality level.
     *
     * @param int|string $level The quality value. (Range 1 to 100)
     *
     * @return static
     */
    public static function level($level)
    {
        return static::createQuality($level);
    }

    /**
     * Quality auto.
     *
     * @param $preset
     *
     * @return static
     */
    public static function auto($preset = null)
    {
        return static::createQuality(QualityQualifier::AUTO, $preset);
    }

    /**
     * Quality good.
     *
     * @return static
     */
    public static function autoGood()
    {
        return static::auto(QualityQualifier::GOOD);
    }

    /**
     * Quality best.
     *
     * @return static
     */
    public static function autoBest()
    {
        return static::auto(QualityQualifier::BEST);
    }

    /**
     * Quality eco.
     *
     * @return static
     */
    public static function autoEco()
    {
        return static::auto(QualityQualifier::ECO);
    }

    /**
     * Quality low.
     *
     * @return static
     */
    public static function autoLow()
    {
        return static::auto(QualityQualifier::LOW);
    }

    /**
     * Quality jpegmini.
     *
     * @param int $level The quality level. Use the constants defined in the JpegMini class.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\JpegMini
     */
    public static function jpegmini($level = null)
    {
        return static::createQuality(QualityQualifier::JPEG_MINI, $level);
    }

    /**
     * Quality jpegminiBest.
     *
     * Alias for jpegmini(JpegMini::BEST)
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\JpegMini
     */
    public static function jpegminiBest()
    {
        return static::jpegmini(JpegMini::BEST);
    }
    /**
     * Quality jpegminiHigh.
     *
     * Alias for jpegmini(JpegMini::HIGH)
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\JpegMini
     */
    public static function jpegminiHigh()
    {
        return static::jpegmini(JpegMini::HIGH);
    }

    /**
     * Quality jpegminiMedium.
     *
     * Alias for jpegmini(JpegMini::MEDIUM)
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\JpegMini
     */
    public static function jpegminiMedium()
    {
        return static::jpegmini(JpegMini::MEDIUM);
    }

    /**
     * Creates a new instance.
     *
     * @param       $level
     * @param array $values
     *
     * @return static
     *
     * @internal
     */
    protected static function createQuality($level, ...$values)
    {
        return new static($level, ...$values);
    }
}
