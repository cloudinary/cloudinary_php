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
use Cloudinary\Transformation\Expression\ExpressionUtils;
use Cloudinary\Transformation\Qualifier\Dimensions\Dimensions;
use Cloudinary\Transformation\Qualifier\Dimensions\DimensionsTrait;

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

        $this->addQualifier(ClassUtils::verifyInstance($cropMode, CropMode::class));

        $this->width($width)->height($height);
    }

    /**
     * Creates a new instance using provided array of qualifiers
     *
     * @param array $qualifiers The qualifiers.
     *
     * @return static
     */
    public static function fromParams($qualifiers)
    {
        return new static(
            ArrayUtils::get($qualifiers, 'crop'),
            ArrayUtils::get($qualifiers, 'width'),
            ArrayUtils::get($qualifiers, 'height')
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
    protected function ignoreAspectRatio($ignoreAspectRatio = true)
    {
        return $this->setFlag(Flag::ignoreAspectRatio(), $ignoreAspectRatio);
    }

    /**
     * Sets the resize mode.
     *
     * @param string|FlagQualifier $resizeMode The resize mode. Can be set to: relative or regionRelative.
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
     * Modifies percentage-based width & height qualifiers of overlays and underlays (e.g., 1.0) to be relative to the
     * overlaid region. Currently regions are only defined when using gravity 'face', 'faces' or 'custom'.
     *
     * @return static
     */
    public function regionRelative()
    {
        return $this->setFlag(new FlagQualifier(Flag::REGION_RELATIVE));
    }

    /**
     * Modifies percentage-based width & height qualifiers of overlays and underlays (e.g., 1.0) to be relative to the
     * containing image instead of the added layer.
     *
     * @return static
     */
    public function relative()
    {
        return $this->setFlag(new FlagQualifier(Flag::RELATIVE));
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
        if (! isset($this->qualifiers[Dimensions::getName()])) {
            $this->addQualifier(new Dimensions());
        }

        if ($value instanceof AspectRatio
            && (string)$value->getValue() === ExpressionUtils::normalize(AspectRatio::IGNORE_INITIAL)
        ) {
            $this->ignoreAspectRatio();
        }

        $this->qualifiers[Dimensions::getName()]->addQualifier($value);

        return $this;
    }
}
