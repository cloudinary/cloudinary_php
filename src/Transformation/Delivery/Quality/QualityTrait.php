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
        return static::createQuality(QualityParam::AUTO, $preset);
    }

    /**
     * Quality good.
     *
     * @return static
     */
    public static function good()
    {
        return static::auto(QualityParam::GOOD);
    }

    /**
     * Quality best.
     *
     * @return static
     */
    public static function best()
    {
        return static::auto(QualityParam::BEST);
    }

    /**
     * Quality eco.
     *
     * @return static
     */
    public static function eco()
    {
        return static::auto(QualityParam::ECO);
    }

    /**
     * Quality low.
     *
     * @return static
     */
    public static function low()
    {
        return static::auto(QualityParam::LOW);
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
    public static function jpegMini($level = null)
    {
        return static::createQuality(QualityParam::JPEG_MINI, $level);
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
