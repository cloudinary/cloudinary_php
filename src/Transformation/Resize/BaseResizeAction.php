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

use Cloudinary\ArrayUtils;
use Cloudinary\ClassUtils;
use Cloudinary\Transformation\Parameter\Dimensions\Dimensions;
use Cloudinary\Transformation\Parameter\Dimensions\DimensionsTrait;

/**
 * Class BaseResizeAction
 *
 * @api
 */
abstract class BaseResizeAction extends BaseAction
{
    /**
     * @var string $name The resize action name.
     */
    protected static $name = 'resize';

    use DimensionsTrait;

    /**
     * BaseResize constructor.
     *
     * @param string|CropMode $cropMode The crop mode.
     * @param null            $width    Optional. Width.
     * @param null            $height   Optional. Height.
     */
    public function __construct($cropMode, $width = null, $height = null)
    {
        parent::__construct();

        $this->addParameter(ClassUtils::verifyInstance($cropMode, CropMode::class));

        $this->width($width)->height($height);
    }

    /**
     * Creates a new instance using provided array of parameters
     *
     * @param array $params The parameters.
     *
     * @return static
     */
    public static function fromParams($params)
    {
        return new static(
            ArrayUtils::get($params, 'crop'),
            ArrayUtils::get($params, 'width'),
            ArrayUtils::get($params, 'height')
        );
    }

    /**
     * Allow specifying only either width or height so the value of the second axis remains as is,
     * and is not recalculated to maintain the aspect ratio of the original image.
     *
     * @param bool $ignoreAspectRatio Indicates whether to ignore aspect ratio or not
     *
     * @return static
     */
    public function ignoreAspectRatio($ignoreAspectRatio = true)
    {
        return $this->setFlag(Flag::ignoreAspectRatio(), $ignoreAspectRatio);
    }

    /**
     * Sets the resize mode.
     *
     * @param string|FlagParameter $resizeMode The resize mode. Can be set to: relative or regionRelative.
     *
     * @return static
     *
     * @see ResizeMode::relative
     * @see ResizeMode::regionRelative
     */
    public function resizeMode($resizeMode)
    {
        return $this->setFlag($resizeMode);
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
    protected function setDimension($value)
    {
        if (! isset($this->parameters[Dimensions::getName()])) {
            $this->addParameter(new Dimensions());
        }

        $this->parameters[Dimensions::getName()]->addParameter($value);

        return $this;
    }
}
