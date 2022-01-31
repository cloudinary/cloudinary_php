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
 * Defines the global configuration when making requests to the Cloudinary API.
 *
 * @property string    $uploadPrefix    Used for changing default API host.
 * @property int|float $timeout         Describing the timeout of the request in seconds.
 *                                          Use 0 to wait indefinitely (the default value is 60 seconds).
 * @property int|float $uploadTimeout   Describing the timeout of the upload request in seconds.
 *                                          Use 0 to wait indefinitely.
 * @property int       $chunkSize       Size of single chunk when uploading large files.
 *
 * @api
 */
class ApiConfig extends BaseConfigSection
{
    const CONFIG_NAME = 'api';

    const DEFAULT_UPLOAD_PREFIX = 'https://api.cloudinary.com';
    const DEFAULT_CHUNK_SIZE    = 20000000; // bytes
    const DEFAULT_TIMEOUT       = 60; // seconds

    // Supported parameters
    const UPLOAD_PREFIX      = 'upload_prefix'; // FIXME: rename it! (it is actually prefix for all API calls)
    const API_PROXY          = 'api_proxy';
    const CONNECTION_TIMEOUT = 'connection_timeout';
    const TIMEOUT            = 'timeout';
    const UPLOAD_TIMEOUT     = 'upload_timeout';
    const CHUNK_SIZE         = 'chunk_size';
    const CALLBACK_URL       = 'callback_url';

    /**
     * Used for changing default API host.
     *
     * @var string
     */
    protected $uploadPrefix;

    /**
     * Optional. Specifies a proxy through which to make calls to the Cloudinary API.  Format: http://hostname:port.
     *
     * @var int $apiProxy
     */
    public $apiProxy;

    /**
     *  Describing the number of seconds to wait while trying to connect to a server.
     *  Use 0 to wait indefinitely (the default behavior).
     *
     * @var int|float
     */
    public $connectionTimeout;

    /**
     * Describing the timeout of the request in seconds.
     * Use 0 to wait indefinitely (the default behavior).
     *
     * @var int|float
     */
    protected $timeout;

    /**
     * Describing the timeout of the upload request in seconds.
     * Use 0 to wait indefinitely (the default behavior).
     *
     * @var int|float
     */
    protected $uploadTimeout;

    /**
     * Size of a single chunk when uploading large files.
     *
     * @var int
     */
    protected $chunkSize;

    /**
     * A public URL of your web application that has the cloudinary_cors.html file.
     *
     * @var string
     */
    public $callbackUrl;
}
