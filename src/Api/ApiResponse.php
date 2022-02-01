<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Api;

use ArrayObject;
use Cloudinary\ArrayUtils;

/**
 * Defines the response returned by the API.
 *
 * @api
 */
class ApiResponse extends ArrayObject
{
    /**
     * Unix timestamp of the time the hourly count will be reset.
     *
     * @var false|int
     */
    public $rateLimitResetAt;

    /**
     * Per-hour limit.
     *
     * @var int
     */
    public $rateLimitAllowed;

    /**
     * Remaining number of actions.
     *
     * @var int
     */
    public $rateLimitRemaining;

    /**
     * Response headers.
     *
     * @var array $headers
     */
    public $headers;

    /**
     * ApiResponse constructor.
     *
     * @param $responseJson
     * @param $headers
     */
    public function __construct($responseJson, $headers)
    {
        $this->headers = $headers;
        // According to RFC 2616, header names are case-insensitive.
        $lcHeaders = array_change_key_case($headers, CASE_LOWER);

        $this->rateLimitResetAt   = strtotime(ArrayUtils::get($lcHeaders, ['x-featureratelimit-reset', 0], 0));
        $this->rateLimitAllowed   = (int)ArrayUtils::get($lcHeaders, ['x-featureratelimit-limit', 0], 0);
        $this->rateLimitRemaining = (int)ArrayUtils::get($lcHeaders, ['x-featureratelimit-remaining', 0], 0);

        parent::__construct($responseJson);
    }
}
