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
 * Trait GenericResizeTrait
 *
 * @api
 */
trait GenericResizeTrait
{
    /**
     * Generic resize builder.
     *
     * @param string                $resizeName Provide future (not supported in the current version) resize name.
     * @param int|float|string|null $width      The required width of a transformed asset.
     * @param int|float|null        $height     The required height of a transformed asset.
     *
     * @return static
     */
    public static function generic(
        $resizeName,
        $width = null,
        $height = null
    ) {
        return static::createGenericResize($resizeName, $width, $height);
    }

    /**
     * Creates GenericResize instance.
     *
     * @param mixed ...$args
     *
     * @return static
     *
     * @internal
     */
    protected static function createGenericResize(...$args)
    {
        return new static(...$args);
    }
}
