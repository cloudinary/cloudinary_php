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
     * Rotate image 90 degrees clockwise only if the requested aspect ratio does not match the image's aspect ratio.
     *
     * @return static
     */
    public static function autoRight()
    {
        return static::createWithDegree(Degree::AUTO_RIGHT);
    }

    /**
     * Rotate image 90 degrees counterclockwise only if the requested aspect ratio does not match the image's aspect
     * ratio.
     *
     * @return static
     */
    public static function autoLeft()
    {
        return static::createWithDegree(Degree::AUTO_LEFT);
    }

    /**
     * Vertical mirror flip of the image.
     *
     * @return static
     */
    public static function verticalFlip()
    {
        return static::createWithDegree(Degree::VERTICAL_FLIP);
    }

    /**
     * Horizontal mirror flip of the image.
     *
     * @return static
     */
    public static function horizontalFlip()
    {
        return static::createWithDegree(Degree::HORIZONTAL_FLIP);
    }

    /**
     * By default, the image is automatically rotated according to the EXIF data stored by the camera when the image
     * was taken. Set the angle to 'ignore' if you do not want the image to be automatically rotated.
     *
     * @return static
     */
    public static function ignore()
    {
        return static::createWithDegree(Degree::IGNORE);
    }

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
    public static function angle(...$degree)
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
