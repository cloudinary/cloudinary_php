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
 * Trait AutoGravityBuilderTrait
 *
 * @api
 */
trait AutoGravityBuilderTrait
{
    /**
     * Sets automatic gravity.
     *
     * An intelligent algorithm analyzes and prioritizes the most prominent elements of the asset to include.
     *
     * @param mixed ...$fallback Fallback gravities.
     *
     * @return AutoGravity
     */
    public static function auto(...$fallback)
    {
        return self::createWithAutoGravity(AutoGravity::AUTO, ...$fallback);
    }

    /**
     * Alias for Gravity::auto()
     *
     * @param mixed ...$fallback
     *
     * @return AutoGravity
     *
     * @see AutoGravity::auto
     */
    public static function autoGravity(...$fallback)
    {
        return self::auto(...$fallback);
    }

    /**
     * Creates a new instance of the AutoGravity class.
     *
     * @param string $gravity  The main gravity.
     * @param array  $fallback Fallback gravities.
     *
     * @return AutoGravity
     */
    protected static function createWithAutoGravity($gravity, ...$fallback)
    {
        return new AutoGravity($gravity, ...$fallback);
    }
}
