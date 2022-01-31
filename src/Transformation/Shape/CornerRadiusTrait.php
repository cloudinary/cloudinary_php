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
 * Trait CornerRadiusTrait
 *
 * @api
 */
trait CornerRadiusTrait
{
    /**
     * Generates an asset with a circular crop using the 'max' radius value.
     *
     * @return static
     */
    public static function max()
    {
        return static::createWithRadius(CornerRadius::MAX);
    }

    /**
     * Rounds the specified corners of an image by specifying 1-4 pixel values as follows:
     *
     * 1 value: All four corners are rounded equally according to the specified value.
     * 2 values: 1st value => top-left & bottom-right. 2nd value => top-right & bottom-left.
     * 3 values: 1st value => top-left. 2nd value => top-right & bottom-left. 3rd value => bottom-right.
     * 4 values: Each corner specified separately, in clockwise order, starting with top-left.
     *
     * @param array $values
     *
     * @return static
     */
    public static function byRadius(...$values)
    {
        return static::createWithRadius(...$values);
    }

    /**
     * Internal named constructor.
     *
     * @param array $values
     *
     * @return static
     *
     * @internal
     */
    protected static function createWithRadius(...$values)
    {
        return new CornerRadius(...$values);
    }
}
