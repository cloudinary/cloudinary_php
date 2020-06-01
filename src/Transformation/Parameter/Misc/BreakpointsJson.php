<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Parameter\Misc;

use Cloudinary\Configuration\ResponsiveBreakpointsConfig;
use Cloudinary\Transformation\Parameter\BaseParameter;
use Cloudinary\Utils;

/**
 * Class BreakpointsJson
 *
 * An alias for w_auto:breakpoints:json parameter
 *
 * @api
 */
class BreakpointsJson extends BaseParameter
{
    /**
     * @var string $name The name of the parameter. Actually width parameter is used for this purpose
     */
    protected static $name = 'width';

    /**
     * @var string $name The key of the parameter.
     */
    protected static $key = 'w';

    /**
     * @var int The minimum width needed for this image. Default: 50.
     */
    protected $minWidth;

    /**
     * @var int The maximum width needed for this image. If specifying a width bigger than the original image,
     * the width of the original image is used instead. Default: 1000.
     */
    protected $maxWidth;

    /**
     * @var int The minimum number of bytes between two consecutive breakpoints (images). Default: 20000.
     */
    protected $bytesStep;

    /**
     * @var int The maximum number of breakpoints to find, between 3 and 200. This means that there might be size
     * differences bigger than the given bytes_step value between consecutive images. Default: 20.
     */
    protected $maxImages;

    /**
     * BreakpointsJson parameter constructor.
     *
     * @param int $minWidth  The minimum width needed for this image. Default: 50.
     * @param int $maxWidth  The maximum width needed for this image. If specifying a width bigger than the original
     *                       image, the width of the original image is used instead. Default: 1000.
     * @param int $bytesStep The minimum number of bytes between two consecutive breakpoints (images). Default: 20000.
     * @param int $maxImages The maximum number of breakpoints to find, between 3 and 200. This means that there might
     *                       be size differences bigger than the given bytes_step value between consecutive images.
     *                       Default: 20.
     *
     */
    public function __construct($minWidth = null, $maxWidth = null, $bytesStep = null, $maxImages = null)
    {
        parent::__construct();

        $this->minWidth($minWidth);
        $this->maxWidth($maxWidth);
        $this->bytesStep($bytesStep);
        $this->maxImages($maxImages);
    }

    /**
     * Sets the minimum width.
     *
     * @param int $minWidth The minimum width needed for this image. Default: 50.
     *
     * @return BreakpointsJson
     */
    public function minWidth($minWidth)
    {
        $this->minWidth = $minWidth ?: ResponsiveBreakpointsConfig::DEFAULT_MIN_WIDTH;

        return $this;
    }

    /**
     * Sets the maximum width.
     *
     * @param int $maxWidth The maximum width needed for this image. If specifying a width bigger than the original
     *                      image, the width of the original image is used instead. Default: 1000.
     *
     * @return BreakpointsJson
     */
    public function maxWidth($maxWidth)
    {
        $this->maxWidth = $maxWidth ?: ResponsiveBreakpointsConfig::DEFAULT_MAX_WIDTH;

        return $this;
    }

    /**
     * Sets the size step (in bytes).
     *
     * @param int $bytesStep The minimum number of bytes between two consecutive breakpoints (images). Default: 20000.
     *
     * @return BreakpointsJson
     */
    public function bytesStep($bytesStep)
    {
        $this->bytesStep = $bytesStep ?: ResponsiveBreakpointsConfig::DEFAULT_BYTES_STEP;

        return $this;
    }

    /**
     * Sets the maximum amount of breakpoints.
     *
     * @param int $maxImages The maximum number of breakpoints to find, between 3 and 200. This means that there might
     *                       be size differences bigger than the given bytes_step value between consecutive images.
     *                       Default: 20.
     *
     * @return BreakpointsJson
     */
    public function maxImages($maxImages)
    {
        $this->maxImages = $maxImages ?: ResponsiveBreakpointsConfig::DEFAULT_MAX_IMAGES;

        return $this;
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        return self::getKey() . '_auto:breakpoints_' . "{$this->minWidth}_{$this->maxWidth}_" .
               Utils::bytesToKB($this->bytesStep) . "_{$this->maxImages}:json";
    }

    /**
     * Serializes to json.
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        // TODO: finalize serialization
        return [
            self::$name =>
                [
                    'auto' =>
                        [
                            'breakpoints' =>
                                [
                                    'min_width'  => $this->minWidth,
                                    'max_width'  => $this->maxWidth,
                                    'bytes_step' => $this->bytesStep,
                                    'max_images' => $this->maxImages,

                                ],
                        ],
                ],
        ];
    }
}
