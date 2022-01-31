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

use Cloudinary\Utils;

/**
 * Defines the cloud configuration required to connect your application to Cloudinary.
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/how_to_integrate_cloudinary#get_familiar_with_the_cloudinary_console"
 * target="_blank">Get account details from the Cloudinary Console.</a>
 *
 * @property string signatureAlgorithm By default, set to self::DEFAULT_SIGNATURE_ALGORITHM.
 *
 * @api
 */
class CloudConfig extends BaseConfigSection
{
    use CloudConfigTrait;

    const CONFIG_NAME = 'cloud';

    const DEFAULT_SIGNATURE_ALGORITHM = Utils::ALGO_SHA1;

    // Supported parameters
    const CLOUD_NAME          = 'cloud_name';
    const API_KEY             = 'api_key';
    const API_SECRET          = 'api_secret';
    const OAUTH_TOKEN         = 'oauth_token';
    const SIGNATURE_ALGORITHM = 'signature_algorithm';

    /**
     * @var array of configuration keys that contain sensitive data that should not be exported (for example api key)
     */
    protected static $sensitiveDataKeys = [self::API_KEY, self::API_SECRET, self::OAUTH_TOKEN];

    /**
     * Mandatory. The name of your Cloudinary cloud. Used to build the public URL for all your media assets.
     *
     * @var string
     */
    public $cloudName;

    /**
     * Mandatory for server-side operations. Used together with the API secret to communicate with the Cloudinary API
     * and sign requests.
     *
     * @var string
     */
    public $apiKey;

    /**
     * Mandatory for server-side operations. Used together with the API key to communicate with the Cloudinary API and
     * sign requests.
     *
     * @var string
     */
    public $apiSecret;

    /**
     * Optional for sever-side operations. Can be passed instead of passing API key and API secret.
     *
     * @var string
     */
    public $oauthToken;

    /**
     * Sets a signature algorithm (SHA1 by default).
     *
     * @var string
     */
    protected $signatureAlgorithm;

    /**
     * Serialises configuration section to a string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString([self::CLOUD_NAME, self::API_KEY, self::API_SECRET]);
    }


    /**
     * Sets the configuration key with the specified value.
     *
     * @param string $configKey   The configuration key.
     * @param mixed  $configValue THe configuration value.
     *
     * @return $this
     *
     * @internal
     */
    public function setCloudConfig($configKey, $configValue)
    {
        return $this->setConfig($configKey, $configValue);
    }
}
