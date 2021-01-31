<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Qualifier\Dimensions;

use Cloudinary\ClassUtils;
use Cloudinary\Transformation\AspectRatio;
use Cloudinary\Transformation\Expression\Expression;

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
     * @param int|string|Expression $width The width in pixels.
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
     * @param int|string|Expression $height The height in pixels.
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
     * @param float|array|AspectRatio $aspectRatio The new aspect ratio, specified as a percentage or ratio.
     *
     * @return static
     */
    public function aspectRatio(...$aspectRatio)
    {
        $this->setDimension(ClassUtils::verifyVarArgsInstance($aspectRatio, AspectRatio::class));

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
