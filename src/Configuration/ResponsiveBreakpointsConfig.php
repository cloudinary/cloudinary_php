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

/**
 * Defines the global responsive breakpoints configuration.
 * **Learn more**:
 * <a href="https://cloudinary.com/documentation/image_upload_api_reference#responsive_breakpoints_parameter_request_settings" target="_blank">Responsive breakpoints</a>
 *
 * @property int $minWidth  The minimum width needed for the image. Default: 200.
 * @property int $maxWidth  The maximum width needed for the image. Default 1400.
 * @property int $maxImages The maximal number of breakpoints.
 *
 * @api
 */
class ResponsiveBreakpointsConfig extends BaseConfigSection
{
    const CONFIG_NAME = 'responsive_breakpoints';

    const DEFAULT_MIN_WIDTH  = 200;
    const DEFAULT_MAX_WIDTH  = 1400;
    const DEFAULT_MAX_IMAGES = 5;

    // Supported parameters
    const BREAKPOINTS = 'breakpoints';

    const MIN_WIDTH      = 'min_width';
    const MAX_WIDTH      = 'max_width';
    const MAX_IMAGES     = 'max_images';
    const TRANSFORMATION = 'transformation';

    const AUTO_OPTIMAL_BREAKPOINTS = 'auto_optimal_breakpoints';

    /**
     * An array of static breakpoints to use (overrides Cloudinary-optimized breakpoints).
     *
     * @var array
     */
    public $breakpoints;

    /**
     * The minimum width needed for the image. Default: 200.
     *
     * @var int
     */
    protected $minWidth;

    /**
     * The maximum width needed for the image. If specifying a width bigger than the original image,
     * the width of the original image is used instead. Default: 1400.
     *
     * @var int
     */
    protected $maxWidth;

    /**
     * The number of breakpoints. Default: 5.
     *
     * @var int
     */
    protected $maxImages;

    /**
     * (Optional) The base transformation to first apply to the image before finding the best breakpoints.
     * The API accepts a string representation of a chained transformation (same as the regular transformation parameter
     * of the upload API).
     *
     * @var array|string
     */
    public $transformation;


    /**
     * Defines whether to use auto optimal breakpoints.
     *
     * @var bool
     */
    public $autoOptimalBreakpoints;
}
