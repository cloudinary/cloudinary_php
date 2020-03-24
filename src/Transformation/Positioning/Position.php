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
 * Class Position
 *
 * @api
 */
abstract class Position
{
    use CompassGravityBuilderTrait;
    use FocalGravityBuilderTrait;

    /**
     * Absolute position on the canvas.
     *
     * @param int|float|string $x The x coordinate.
     * @param int|float|string $y The y coordinate.
     *
     * @return AbsolutePosition
     */
    public static function absolute($x = null, $y = null)
    {
        return new AbsolutePosition($x, $y);
    }

    /**
     * Named constructor.
     *
     * @param string|CompassGravity $direction The compass gravity..
     *
     * @param array                 $args      Additional arguments.
     *
     * @return CompassPosition
     *
     * @internal
     */
    protected static function createWithCompassGravity($direction, ...$args)
    {
        return new CompassPosition($direction, ...$args);
    }

    /**
     * Named constructor.
     *
     * @param string|FocalGravity $gravity  The focal gravity.
     * @param array               $fallback Fallback gravities.
     *
     * @return FocalPosition
     *
     * @internal
     */
    protected static function createWithFocalGravity($gravity, ...$fallback)
    {
        return new FocalPosition($gravity);
    }
}
