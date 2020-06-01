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
 * Class AccountConfig
 *
 * @api
 */
class AccountConfig extends BaseConfigSection
{
    use AccountConfigTrait;

    const CONFIG_NAME = 'account';

    // Supported parameters
    const CLOUD_NAME = 'cloud_name';
    const API_KEY    = 'api_key';
    const API_SECRET = 'api_secret';

    /**
     * @var array of configuration keys that contain sensitive data that should not be exported (for example api key)
     */
    protected static $sensitiveDataKeys = [self::API_KEY, self::API_SECRET];

    /**
     * Mandatory. The name of your Cloudinary account. Used to build the public URL for all your media assets.
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
    public function setAccountConfig($configKey, $configValue)
    {
        return $this->setConfig($configKey, $configValue);
    }
}
