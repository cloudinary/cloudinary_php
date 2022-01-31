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
class Position extends BasePosition
{
    use OffsetTrait;
    use PointTrait;
    use CompassGravityBuilderTrait;
    use FocalGravityBuilderTrait;
    use GravityTrait;

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

    /**
     * @param $value
     *
     * @return static
     * @internal
     *
     */
    public function setOffsetValue($value)
    {
        if (! isset($this->qualifiers[Offset::getName()])) {
            $this->addQualifier(new Offset());
        }

        $this->qualifiers[Offset::getName()]->addQualifier($value);

        return $this;
    }

    /**
     * Internal setter for the point value.
     *
     * @param mixed $value
     *
     * @return static
     *
     * @internal
     */
    public function setPointValue($value)
    {
        if (! isset($this->qualifiers[AbsolutePosition::getName()])) {
            $this->addQualifier(self::absolute());
        }

        $this->qualifiers[AbsolutePosition::getName()]->setPointValue($value);

        return $this;
    }
}
