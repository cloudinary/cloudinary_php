<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Argument;

/**
 * Trait AngleTrait
 *
 * @api
 */
trait AngleTrait
{
    /**
     * Rotate image 90 degrees clockwise.
     *
     * @return static
     */
    public static function deg90()
    {
        return static::createWithDegree(Degree::DEG_90);
    }

    /**
     * Rotate image 180 degrees.
     *
     * @return static
     */
    public static function deg180()
    {
        return static::createWithDegree(Degree::DEG_180);
    }

    /**
     * Rotate image 90 degrees counterclockwise.
     *
     * @return static
     */
    public static function deg270()
    {
        return static::createWithDegree(Degree::DEG_270);
    }

    /**
     * Rotate an image by the given degrees.
     *
     * @param int|array $degree Given degrees. (Range: 0 to 360, Default: 0).
     *
     * @return static
     */
    public static function byAngle(...$degree)
    {
        return static::createWithDegree(...$degree);
    }

    /**
     * Creates the instance.
     *
     * @param int|array $degree Given degrees or mode.
     *
     * @return static
     *
     * @internal
     */
    protected static function createWithDegree(...$degree)
    {
        return new static(...$degree);
    }
}
