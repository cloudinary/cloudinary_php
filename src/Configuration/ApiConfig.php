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
 * Class ApiConfig
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
     * @var string Used for changing default API host.
     */
    protected $uploadPrefix;
    /**
     * @var int $apiProxy Optional. Specifies a proxy through which to make calls to the Cloudinary API.
     *             Format: http://hostname:port.
     */
    public $apiProxy;
    /**
     * @var int|float Describing the number of seconds to wait while trying to connect to a server.
     *                Use 0 to wait indefinitely (the default behavior).
     */
    public $connectionTimeout;
    /**
     * @var int|float Describing the timeout of the request in seconds.
     *                Use 0 to wait indefinitely (the default behavior).
     */
    protected $timeout;
    /**
     * @var int|float Describing the timeout of the upload request in seconds.
     *                Use 0 to wait indefinitely (the default behavior).
     */
    protected $uploadTimeout;
    /**
     * @var int Size of single chunk when uploading large files.
     */
    protected $chunkSize;
    /**
     * @var string A public URL of your web application that has the cloudinary_cors.html file.
     */
    public $callbackUrl;
}
