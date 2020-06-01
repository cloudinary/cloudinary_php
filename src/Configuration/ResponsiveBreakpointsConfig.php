<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Configuration;

use Cloudinary\Cache\Adapter\CacheAdapter;

/**
 * Class ResponsiveBreakpointsConfig
 *
 * @api
 */
class ResponsiveBreakpointsConfig extends BaseConfigSection
{
    const CONFIG_NAME = 'responsive_breakpoints';

    const DEFAULT_MIN_WIDTH  = 50;
    const DEFAULT_MAX_WIDTH  = 1000;
    const DEFAULT_BYTES_STEP = 20000;
    const DEFAULT_MAX_IMAGES = 20;

    // Supported parameters
    const BREAKPOINTS = 'breakpoints';

    const MIN_WIDTH      = 'min_width';
    const MAX_WIDTH      = 'max_width';
    const BYTES_STEP     = 'bytes_step';
    const MAX_IMAGES     = 'max_images';
    const TRANSFORMATION = 'transformation';

    const USE_CACHE     = 'use_cache';
    const FETCH_MISSING = 'fetch_missing';
    const CACHE_ADAPTER = 'cache_adapter';

    /**
     * An array of static breakpoints to use (overrides Cloudinary-optimized breakpoints).
     *
     * @var array
     */
    public $breakpoints;

    /**
     * The minimum width needed for this image. Default: 50.
     *
     * @var int
     */
    public $minWidth;

    /**
     * The maximum width needed for this image. If specifying a width bigger than the original image,
     * the width of the original image is used instead. Default: 1000.
     *
     * @var int
     */
    public $maxWidth;

    /**
     * The minimum number of bytes between two consecutive breakpoints (images). Default: 20000.
     *
     * @var int
     */
    public $bytesStep;

    /**
     * The maximum number of breakpoints to find, between 3 and 200. This means that there might be size
     * differences bigger than the given bytes_step value between consecutive images. Default: 20.
     *
     * @var int
     */
    public $maxImages;

    /**
     * (Optional) The base transformation to first apply to the image before finding the best breakpoints.
     * The API accepts a string representation of a chained transformation (same as the regular transformation parameter
     * of the upload API).
     *
     * @var array|string
     */
    public $transformation;

    /**
     * Defines whether to use responsive breakpoints cache or not.
     *
     * @var bool
     */
    public $useCache;

    /**
     * Defines whether to fetch optimal breakpoints from Cloudinary in case of cache miss.
     *
     * @var bool
     */
    public $fetchMissing;

    /**
     * The cache adapter to use to store/retrieve responsive breakpoints.
     *
     * @var CacheAdapter
     */
    public $cacheAdapter;
}
