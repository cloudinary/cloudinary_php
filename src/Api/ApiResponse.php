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
 * Class ApiResponse
 *
 * @api
 */
class ApiResponse extends ArrayObject
{
    /**
     * @var false|int Unix timestamp of the time the hourly count will be reset
     */
    public $rateLimitResetAt;
    /**
     * @var int Per-hour limit
     */
    public $rateLimitAllowed;
    /**
     * @var int Remaining number of actions
     */
    public $rateLimitRemaining;

    /**
     * ApiResponse constructor.
     *
     * @param $responseJson
     * @param $headers
     */
    public function __construct($responseJson, $headers)
    {
        $this->rateLimitResetAt   = strtotime(Arrayutils::get($headers, ['X-FeatureRateLimit-Reset', 0]));
        $this->rateLimitAllowed   = (int)Arrayutils::get($headers, ['X-FeatureRateLimit-Limit', 0]);
        $this->rateLimitRemaining = (int)Arrayutils::get($headers, ['X-FeatureRateLimit-Remaining', 0]);

        parent::__construct($responseJson);
    }
}
