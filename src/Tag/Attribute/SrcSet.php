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
    public const RES_DISTRIBUTION
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
    public const DEFAULT_DPR_THRESHOLD = 768;
    /**
     * @var float DEFAULT_DPR The default DPR for width below DEFAULT_DPR_THRESHOLD.
     */
    public const DEFAULT_DPR = 2.0;

    use LoggerTrait;

    /**
     * @var ?array The list of the breakpoints.
     */
    protected ?array $breakpoints = [];

    /**
     * @var Image $image The Image of the attribute.
     */
    protected mixed $image;

    /**
     * @var ResponsiveBreakpointsConfig $responsiveBreakpointsConfig The configuration instance.
     */
    protected ResponsiveBreakpointsConfig $responsiveBreakpointsConfig;

    /**
     * @var Transformation $transformation The srcset transformation.
     */
    protected Transformation $transformation;

    protected float $relativeWidth;

    /**
     * SrcSet constructor.
     *
     * @param Configuration|null $configuration The Configuration source.
     */
    public function __construct($image, ?Configuration $configuration = null)
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
    public function breakpoints(?array $breakpoints = null): static
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
    public function autoOptimalBreakpoints(bool $autoOptimalBreakpoints = true): static
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
    public function relativeWidth(float $relativeWidth = 1.0): static
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
     */
    private function calculateAutoOptimalBreakpoints(int $minWidth, int $maxWidth, int $maxImages): array
    {
        [$minWidth, $maxWidth, $maxImages] = $this->validateInput($minWidth, $maxWidth, $maxImages);

        [$physicalMinWidth, $physicalMaxWidth] = self::getPhysicalDimensions($minWidth, $maxWidth);

        if ($physicalMinWidth === $physicalMaxWidth) {
            return [$physicalMaxWidth];
        }

        $validBreakpoints = array_filter(
            self::RES_DISTRIBUTION,
            static fn($v) => $v >= $physicalMinWidth && $v <= $physicalMaxWidth
        );

        $res = self::collectFromGroup(
            self::RES_DISTRIBUTION,
            $validBreakpoints,
            $maxImages
        );

        $res = array_unique($res); // remove duplicates

        sort($res);

        return array_map(fn($bp) => (int)ceil($bp * $this->relativeWidth), $res);
    }

    /**
     *
     * @return int[]
     */
    protected static function getPhysicalDimensions($minWidth, $maxWidth): array
    {
        $physicalMinWidth = self::getDprDimension($minWidth);
        $physicalMaxWidth = self::getDprDimension($maxWidth);

        if ($physicalMinWidth > $physicalMaxWidth) {
            [$physicalMinWidth, $physicalMaxWidth] = [$physicalMaxWidth, $physicalMinWidth];
        }

        return [$physicalMinWidth, $physicalMaxWidth];
    }

    protected static function getDprDimension(int $dimension): float|int
    {
        return $dimension < self::DEFAULT_DPR_THRESHOLD ? $dimension * self::DEFAULT_DPR : $dimension;
    }

    /**
     *  Collect breakpoints from group.
     *
     * @param array $group     The group of breakpoints.
     * @param array $whitelist The whitelisted values.
     * @param int   $count     The amount to collect.
     *
     */
    protected static function collectFromGroup(array $group, array $whitelist, int $count = 1): array
    {
        $result = [];

        if ($count < 1) {
            return $result;
        }

        foreach ($group as $res) {
            if (in_array($res, $whitelist) && ! in_array($res, $result)) {
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
     */
    private function validateInput(int $minWidth, int $maxWidth, int $maxImages): ?array
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
                fn($b) => $this->image->toUrl(Resize::scale($b)) . " {$b}w",
                $breakpoints
            )
        );
    }

    /**
     * Gets the breakpoints.
     *
     *
     * @internal
     */
    public function getBreakpoints(): array
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
