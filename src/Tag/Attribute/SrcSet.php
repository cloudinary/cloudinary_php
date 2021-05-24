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
use Cloudinary\StringUtils;
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
    const RES_DISTRIBUTION
        = [
            360  => 29.18,
            1366 => 12.92,
            1920 => 12.06,
            375  => 9.82,
            414  => 8.6,
            412  => 7.7,
            1280 => 4.98,
            1536 => 4.81,
            1440 => 3.97,
            768  => 3.64,
            1600 => 2.35,
        ];

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
        $this->image                       = $image;
        $this->logging                     = $configuration->logging;
        $this->responsiveBreakpointsConfig = $configuration->responsiveBreakpoints;

        $this->breakpoints($configuration->responsiveBreakpoints->breakpoints); // take configuration breakpoints

        $this->transformation($configuration->responsiveBreakpoints->transformation);

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
     * Sets the custom transformation for the srcset.
     *
     * @param array|null $transformation The transformation to set.
     *
     * @return $this
     */
    public function transformation($transformation = null)
    {
        $this->transformation = ClassUtils::forceInstance($transformation, Transformation::class);

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

        $relativeWidth = $this->relativeWidth ?: 1.0;

        $minWidth = (int)floor($minWidth * $relativeWidth);
        $maxWidth = (int)ceil($maxWidth * $relativeWidth);

        $res = [$minWidth, $maxWidth];

        $validBreakpoints = array_filter(
            array_keys(self::RES_DISTRIBUTION),
            static function ($v) use ($minWidth, $maxWidth) {
                return $v > $minWidth && $v < $maxWidth;
            }
        );

        $res = self::collectFromGroup(
            self::RES_DISTRIBUTION,
            $validBreakpoints,
            $res,
            $maxImages - count($res)
        );

        $res = array_unique($res); // remove duplicates
        sort($res);

        return $res;
    }

    /**
     * @param     $group
     * @param     $whitelist
     * @param     $result
     * @param int $count
     *
     * @return mixed
     */
    protected static function collectFromGroup($group, $whitelist, $result, $count = 1)
    {
        if ($count < 1) {
            return $result;
        }

        foreach (array_keys($group) as $res) {
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
            return '';
        }

        return implode(
            ', ',
            array_map(
                function ($b) {
                    $transformationStr    = $this->transformation->toUrl(Resize::scale($b));
                    $appendTransformation = ! StringUtils::contains($transformationStr, '/');

                    return $this->image->toUrl($transformationStr, $appendTransformation) . " {$b}w";
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
