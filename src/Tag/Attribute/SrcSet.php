<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Tag;

use Cloudinary\Asset\Image;
use Cloudinary\ClassUtils;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Configuration\ResponsiveBreakpointsConfig;
use Cloudinary\Log\LoggerTrait;
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\Transformation;
use InvalidArgumentException;

/**
 * Class SrcSet
 *
 * 'srcset' attribute of the img/source tag
 *
 * @internal
 */
class SrcSet
{
    /**
     * @var array RES_DISTRIBUTION The distribution of screen resolutions.
     */
    const RES_DISTRIBUTION
        = [
            1366,
            828,
            1920,
            3840,
            1536,
            750,
            1280,
            1600,
            1440,
        ];

    /**
     * @var int DEFAULT_DPR_THRESHOLD The threshold for switching from DEFAULT_DPR to DPR 1.0.
     */
    const DEFAULT_DPR_THRESHOLD = 768;
    /**
     * @var float DEFAULT_DPR The default DPR for width below DEFAULT_DPR_THRESHOLD.
     */
    const DEFAULT_DPR = 2.0;

    use LoggerTrait;

    /**
     * @var array The list of the breakpoints.
     */
    protected $breakpoints = [];

    /**
     * @var Image $image The Image of the attribute.
     */
    protected $image;

    /**
     * @var ResponsiveBreakpointsConfig $responsiveBreakpointsConfig The configuration instance.
     */
    protected $responsiveBreakpointsConfig;

    /**
     * @var Transformation $transformation The srcset transformation.
     */
    protected $transformation;

    /**
     * @var float
     */
    protected $relativeWidth;

    /**
     * SrcSet constructor.
     *
     * @param               $image
     * @param Configuration $configuration
     */
    public function __construct($image, $configuration = null)
    {
        $this->image                       = ClassUtils::forceInstance($image, Image::class, null, $configuration);
        $this->logging                     = $configuration->logging;
        $this->responsiveBreakpointsConfig = $configuration->responsiveBreakpoints;

        $this->breakpoints($configuration->responsiveBreakpoints->breakpoints); // take configuration breakpoints

        $this->relativeWidth = $configuration->tag->relativeWidth;
    }

    /**
     * Explicitly sets the breakpoints.
     *
     * @param array|null $breakpoints The breakpoints to set.
     *
     * @return $this
     */
    public function breakpoints(array $breakpoints = null)
    {
        $this->breakpoints = $breakpoints;

        return $this;
    }

    /**
     * Defines whether to use auto optimal breakpoints.
     *
     * @param bool $autoOptimalBreakpoints Indicates whether to use auto optimal breakpoints.
     *
     * @return $this
     */
    public function autoOptimalBreakpoints($autoOptimalBreakpoints = true)
    {
        $this->responsiveBreakpointsConfig->autoOptimalBreakpoints = $autoOptimalBreakpoints;

        return $this;
    }

    /**
     * Sets the image relative width.
     *
     * @param float $relativeWidth The percentage of the screen that the image occupies..
     *
     * @return $this
     */
    public function relativeWidth($relativeWidth = 1.0)
    {
        $this->relativeWidth = $relativeWidth;

        return $this;
    }

    /**
     * Calculates the breakpoints in an auto optimal way.
     *
     * @param int $minWidth  The minimum width needed for this image.
     * @param int $maxWidth  The maximum width needed for this image.
     * @param int $maxImages The number of breakpoints to use.
     *
     * @return array
     */
    private function calculateAutoOptimalBreakpoints($minWidth, $maxWidth, $maxImages)
    {
        list($minWidth, $maxWidth, $maxImages) = $this->validateInput($minWidth, $maxWidth, $maxImages);

        list($physicalMinWidth, $physicalMaxWidth) = self::getPhysicalDimensions($minWidth, $maxWidth);

        if ($physicalMinWidth === $physicalMaxWidth) {
            return [$physicalMaxWidth];
        }

        $validBreakpoints = array_filter(
            self::RES_DISTRIBUTION,
            static function ($v) use ($physicalMinWidth, $physicalMaxWidth) {
                return $v >= $physicalMinWidth && $v <= $physicalMaxWidth;
            }
        );

        $res = self::collectFromGroup(
            self::RES_DISTRIBUTION,
            $validBreakpoints,
            $maxImages
        );

        $res = array_unique($res); // remove duplicates

        sort($res);

        $res = array_map(function ($bp) {
            return (int)ceil($bp * $this->relativeWidth);
        }, $res);

        return $res;
    }

    /**
     * @param $minWidth
     * @param $maxWidth
     *
     * @return int[]
     */
    protected static function getPhysicalDimensions($minWidth, $maxWidth)
    {
        $physicalMinWidth = self::getDprDimension($minWidth);
        $physicalMaxWidth = self::getDprDimension($maxWidth);

        if ($physicalMinWidth > $physicalMaxWidth) {
            list($physicalMinWidth, $physicalMaxWidth) = [$physicalMaxWidth, $physicalMinWidth];
        }

        return [$physicalMinWidth, $physicalMaxWidth];
    }

    /**
     * @param int $dimension
     *
     * @return int
     */
    protected static function getDprDimension($dimension)
    {
        return $dimension < self::DEFAULT_DPR_THRESHOLD ? (int)$dimension * self::DEFAULT_DPR : $dimension;
    }

    /**
     * @param     $group
     * @param     $whitelist
     * @param int $count
     *
     * @return array
     */
    protected static function collectFromGroup($group, $whitelist, $count = 1)
    {
        $result = [];

        if ($count < 1) {
            return $result;
        }

        foreach ($group as $res) {
            if (in_array($res, $whitelist, false) && ! in_array($res, $result, false)) {
                $result [] = $res;
                $count--;
                if (! $count) {
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Validates user input.
     *
     * @param int $minWidth  The minimum width needed for this image.
     * @param int $maxWidth  The maximum width needed for this image.
     * @param int $maxImages The number of breakpoints to use.
     *
     * @return array
     */
    private function validateInput($minWidth, $maxWidth, $maxImages)
    {
        // When called without any values, just return null
        if ($minWidth === null && $maxWidth === null && $maxImages === null) {
            return null;
        }

        foreach ([$minWidth, $maxWidth, $maxImages] as $arg) {
            if (empty($arg) || ! is_numeric($arg) || is_string($arg)) {
                $message = 'Either valid (minWidth, maxWidth, maxImages) or breakpoints' .
                           'must be provided to the image srcset attribute';
                $this->getLogger()->critical($message);
                throw new InvalidArgumentException($message);
            }
        }

        if ($minWidth > $maxWidth) {
            $message = 'minWidth must be less than maxWidth';
            $this->getLogger()->critical($message);
            throw new InvalidArgumentException($message);
        }

        if ($maxImages <= 0) {
            $message = 'maxImages must be a positive integer';
            $this->getLogger()->critical($message);
            throw new InvalidArgumentException($message);
        }

        if ($maxImages === 1) {
            // if user requested only 1 image in srcset, we return max_width one
            $minWidth = $maxWidth;
        }

        return [$minWidth, $maxWidth, $maxImages];
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        $breakpoints = $this->getBreakpoints();

        if (empty($breakpoints)) {
            return (string)$this->image;
        }

        return implode(
            ', ',
            array_map(
                function ($b) {
                    return $this->image->toUrl(Resize::scale($b)) . " {$b}w";
                },
                $breakpoints
            )
        );
    }

    /**
     * Gets the breakpoints.
     *
     * @return array
     *
     * @internal
     */
    public function getBreakpoints()
    {
        if (! empty($this->breakpoints)) {
            return $this->breakpoints;
        }

        if (! $this->responsiveBreakpointsConfig->autoOptimalBreakpoints) {
            return [];
        }

        return $this->calculateAutoOptimalBreakpoints(
            $this->responsiveBreakpointsConfig->minWidth,
            $this->responsiveBreakpointsConfig->maxWidth,
            $this->responsiveBreakpointsConfig->maxImages
        );
    }
}
