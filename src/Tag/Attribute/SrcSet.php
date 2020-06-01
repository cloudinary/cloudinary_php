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
use Cloudinary\Cache\ResponsiveBreakpointsCache;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Configuration\ResponsiveBreakpointsConfig;
use Cloudinary\Log\LoggerTrait;
use Cloudinary\Transformation\Scale;
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
     * @var ResponsiveBreakpointsConfig The configuration instance.
     */
    protected $responsiveBreakpointsConfig;

    /**
     * SrcSet constructor.
     *
     * @param               $image
     * @param Configuration $configuration
     */
    public function __construct($image, $configuration = null)
    {
        $this->image = $image;
        $this->logging = $configuration->logging;
        $this->responsiveBreakpointsConfig = $configuration->responsiveBreakpoints;

        $this->breakpoints($configuration->responsiveBreakpoints->breakpoints); // take configuration breakpoints
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
     * Calculates the breakpoints in a static way.
     *
     * @param int $minWidth  The minimum width needed for this image.
     * @param int $maxWidth  The maximum width needed for this image.
     * @param int $maxImages The number of breakpoints to use.
     *
     * @return array
     */
    private function calculateStaticBreakpoints($minWidth, $maxWidth, $maxImages)
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

        $stepSize = (int)ceil(($maxWidth - $minWidth) / ($maxImages > 1 ? $maxImages - 1 : 1));

        $breakpoints = [];

        $currBreakpoint = $minWidth;
        while ($currBreakpoint < $maxWidth) {
            $breakpoints[]  = $currBreakpoint;
            $currBreakpoint += $stepSize;
        }

        $breakpoints[] = $maxWidth;

        return $breakpoints;
    }

    /**
     * Sets the static breakpoints.
     *
     * @param int $minWidth  The minimum width needed for this image.
     * @param int $maxWidth  The maximum width needed for this image.
     * @param int $maxImages The number of breakpoints to use.
     *
     * @return $this
     */
    public function staticBreakpoints($minWidth, $maxWidth, $maxImages)
    {
        $this->breakpoints = $this->calculateStaticBreakpoints($minWidth, $maxWidth, $maxImages);

        return $this;
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
            return '';
        }

        return implode(
            ', ',
            array_map(
                function ($b) {
                    return $this->image->toUrl(Scale::scale($b)) . " {$b}w";
                },
                $breakpoints
            )
        );
    }

    /**
     * Gets the breakpoints.
     *
     * Returns breakpoints if defined, otherwise checks the cache(if configured), otherwise fall backs to static
     * calculation.
     *
     * @return array
     */
    protected function getBreakpoints()
    {
        if (! empty($this->breakpoints)) {
            return $this->breakpoints;
        }

        $breakpoints = [];

        if ($this->responsiveBreakpointsConfig->useCache) {
            $breakpoints = ResponsiveBreakpointsCache::instance()->get($this->image, true);
        }

        if (empty($breakpoints)) {
            // Static calculation if cache is not enabled or we failed to fetch breakpoints
            $breakpoints = $this->calculateStaticBreakpoints(
                $this->responsiveBreakpointsConfig->minWidth,
                $this->responsiveBreakpointsConfig->maxWidth,
                $this->responsiveBreakpointsConfig->maxImages
            );
        }

        return $breakpoints;
    }
}
