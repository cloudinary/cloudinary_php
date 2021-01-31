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

use Cloudinary\Transformation\BaseAction;

/**
 * Class Dimensions
 */
class Dimensions extends BaseAction
{
    use DimensionsTrait;

    /**
     * Dimensions constructor.
     *
     * @param null  $width
     * @param null  $height
     * @param mixed ...$aspectRatio
     */
    public function __construct($width = null, $height = null, ...$aspectRatio)
    {
        parent::__construct();

        $this->width($width);
        $this->height($height);
        $this->aspectRatio(...$aspectRatio);
    }

    /**
     * Internal setter for the dimensions.
     *
     * @param Dimensions|mixed $value The dimensions.
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
