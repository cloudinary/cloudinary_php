<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Parameter\Dimensions;

use Cloudinary\ArrayUtils;
use Cloudinary\ClassUtils;

/**
 * Trait DimensionsTrait
 *
 * @api
 */
trait DimensionsTrait
{
    /**
     * Sets the width of the asset.
     *
     * @param int $width The width in pixels.
     *
     * @return static
     */
    public function width($width)
    {
        $this->setDimension(ClassUtils::verifyInstance($width, Width::class));

        return $this;
    }

    /**
     * Sets the height of the asset.
     *
     * @param int $height The height in pixels.
     *
     * @return static
     */
    public function height($height)
    {
        $this->setDimension(ClassUtils::verifyInstance($height, Height::class));

        return $this;
    }

    /**
     * Sets the width and height of the asset.
     *
     * @param string $size The size of asset expressed as "width x height".
     *
     * @return $this
     */
    public function size($size)
    {
        list($width, $height) = explode('x', $size);

        $this->width($width)->height($height);

        return $this;
    }

    /**
     * Sets the aspect ratio of the asset.
     *
     * @param float|array $aspectRatio The new aspect ratio, specified as a percentage or ratio.
     *
     * @return static
     */
    public function aspectRatio(...$aspectRatio)
    {
        if (! empty($aspectRatio) && ! (ArrayUtils::get($aspectRatio, 0) instanceof AspectRatio)) {
            $aspectRatio = [new AspectRatio(...$aspectRatio)];
        }

        $this->setDimension(...$aspectRatio);

        return $this;
    }

    /**
     * Internal setter for the dimensions.
     *
     * @param mixed $value
     *
     * @return static
     */
    abstract protected function setDimension($value);
}
