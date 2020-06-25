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
 * Determines how to crop, scale, and/or zoom the delivered asset according to the requested dimensions.
 *
 * **Learn more**:
 * <a href=https://cloudinary.com/documentation/image_transformations#resizing_and_cropping_images
 * target="_blank">Resizing images</a> |
 * <a href=https://cloudinary.com/documentation/video_manipulation_and_delivery#resizing_and_cropping_videos
 * target="_blank">Resizing videos</a>
 *
 * @api
 */
class Resize extends BaseResizeAction
{
    use ResizeTrait;

    /**
     * Creates a Scale resize action instance.
     *
     * @param mixed ...$args Constructor arguments.
     *
     * @return Scale
     */
    protected static function createScale(...$args)
    {
        return new Scale(...$args);
    }

    /**
     * Creates a Crop resize action instance.
     *
     * @param mixed ...$args Constructor arguments.
     *
     * @return Crop
     */
    protected static function createCrop(...$args)
    {
        return new Crop(...$args);
    }

    /**
     * Creates a Pad resize action instance.
     *
     * @param mixed ...$args Constructor arguments.
     *
     * @return Pad
     */
    protected static function createPad(...$args)
    {
        return new Pad(...$args);
    }

    /**
     * Creates a Fill resize action instance.
     *
     * @param mixed ...$args Constructor arguments.
     *
     * @return Fill
     */
    protected static function createFill(...$args)
    {
        return new Fill(...$args);
    }

    /**
     * Creates a FillPad resize action instance.
     *
     * @param mixed ...$args Constructor arguments.
     *
     * @return FillPad
     */
    protected static function createFillPad(...$args)
    {
        return new FillPad(...$args);
    }

    /**
     * Creates an Imagga resize action instance.
     *
     * @param mixed ...$args Constructor arguments.
     *
     * @return Imagga
     */
    protected static function createImagga(...$args)
    {
        return new Imagga(...$args);
    }
}
