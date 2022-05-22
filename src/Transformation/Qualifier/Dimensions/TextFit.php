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

use Cloudinary\ClassUtils;
use Cloudinary\Transformation\Expression\Expression;
use Cloudinary\Transformation\Qualifier\Dimensions\Height;
use Cloudinary\Transformation\Qualifier\Dimensions\Width;

/**
 * Class TextFit
 */
class TextFit extends BaseAction
{
    /**
     * TextFit constructor.
     *
     * @param int|string|Expression $width  The width in pixels.
     * @param int|string|Expression $height The height in pixels.
     */
    public function __construct($width, $height = null)
    {
        parent::__construct(CropMode::fit());

        $this->width($width);
        $this->height($height);
    }

    /**
     * TextFit named constructor.
     *
     * @param int|string|Expression $width  The width in pixels.
     * @param int|string|Expression $height The height in pixels.
     *
     * @return $this
     */
    public static function size($width, $height = null)
    {
        return new TextFit($width, $height);
    }

    /**
     * Sets the width of the text.
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
     * Sets the height of the text.
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
     * Internal setter for the dimensions.
     *
     * @param mixed $value The dimension.
     *
     * @return static
     *
     * @internal
     */
    protected function setDimension($value = null)
    {
        $this->addQualifier($value);

        return $this;
    }
}
