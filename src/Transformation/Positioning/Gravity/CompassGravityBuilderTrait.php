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
 * Trait CompassGravityTrait
 *
 * @api
 */
trait CompassGravityBuilderTrait
{
    /**
     * North west corner (top left).
     *
     * @param array $args Additional arguments.
     *
     * @return CompassGravity|CompassPosition
     */
    public static function northWest(...$args)
    {
        return static::createWithCompassGravity(Compass::NORTH_WEST, ...$args);
    }

    /**
     * North center part (top center).
     *
     * @param array $args Additional arguments.
     *
     * @return CompassGravity|CompassPosition
     */
    public static function north(...$args)
    {
        return static::createWithCompassGravity(Compass::NORTH, ...$args);
    }

    /**
     * North east corner (top right).
     *
     * @param array $args Additional arguments.
     *
     * @return CompassGravity|CompassPosition
     */
    public static function northEast(...$args)
    {
        return static::createWithCompassGravity(Compass::NORTH_EAST, ...$args);
    }

    /**
     * Middle west part (left).
     *
     * @param array $args Additional arguments.
     *
     * @return CompassGravity|CompassPosition
     */
    public static function west(...$args)
    {
        return static::createWithCompassGravity(Compass::WEST, ...$args);
    }

    /**
     * The center of the image.
     *
     * @param array $args Additional arguments.
     *
     * @return CompassGravity|CompassPosition
     */
    public static function center(...$args)
    {
        return static::createWithCompassGravity(Compass::CENTER, ...$args);
    }

    /**
     * Middle east part (right).
     *
     * @param array $args Additional arguments.
     *
     * @return CompassGravity|CompassPosition
     */
    public static function east(...$args)
    {
        return static::createWithCompassGravity(Compass::EAST, ...$args);
    }

    /**
     * South west corner (bottom left).
     *
     * @param array $args Additional arguments.
     *
     * @return CompassGravity|CompassPosition
     */
    public static function southWest(...$args)
    {
        return static::createWithCompassGravity(Compass::SOUTH_WEST, ...$args);
    }

    /**
     * South center part (bottom center).
     *
     * @param array $args Additional arguments.
     *
     * @return CompassGravity|CompassPosition
     */
    public static function south(...$args)
    {
        return static::createWithCompassGravity(Compass::SOUTH, ...$args);
    }

    /**
     * South east corner (bottom right).
     *
     * @param array $args Additional arguments.
     *
     * @return CompassGravity|CompassPosition
     */
    public static function southEast(...$args)
    {
        return static::createWithCompassGravity(Compass::SOUTH_EAST, ...$args);
    }

    /**
     * Sets the center of gravity to the given x & y coordinates.
     *
     * @param array $args Additional arguments.
     *
     * @return CompassGravity|CompassPosition
     */
    public static function xyCenter(...$args)
    {
        return static::createWithCompassGravity(Compass::XY_CENTER, ...$args);
    }

    /**
     * Creates a new instance of the CompassGravity class.
     *
     * @param string $direction The gravity direction.
     * @param array  $args      Additional arguments.
     *
     * @return CompassGravity
     */
    public static function compass($direction, ...$args)
    {
        return static::createWithCompassGravity($direction, ...$args);
    }

    /**
     * Creates a new instance of the CompassGravity class.
     *
     * @param string $direction The gravity direction.
     * @param array  $args      Additional arguments.
     *
     * @return CompassGravity
     */
    protected static function createWithCompassGravity($direction, ...$args)
    {
        return new CompassGravity($direction);
    }
}
