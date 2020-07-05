<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Configuration\Provisioning;

use Cloudinary\Configuration\ApiConfig;
use Cloudinary\Configuration\LoggingConfig;
use Cloudinary\Exception\ConfigurationException;
use Cloudinary\JsonUtils;
use Cloudinary\StringUtils;
use Psr\Http\Message\UriInterface;

/**
 * Class ProvisioningConfiguration
 *
 * @package Cloudinary
 *
 * @api
 */
class ProvisioningConfiguration
{
    const CLOUDINARY_ACCOUNT_URL_ENV_VAR = 'CLOUDINARY_ACCOUNT_URL';

    /**
     * @var static Singleton instance for the ConfigurationAccount
     */
    private static $instance;

    /**
     * @var ProvisioningAccountConfig $provisioningAccount
     */
    public $provisioningAccount;

    /**
     * @var ApiConfig $api The configuration of the API.
     */
    public $api;

    /**
     * @var LoggingConfig $logging
     */
    public $logging;

    /**
     * @var array Main configuration sections
     */
    protected $sections = [
        ProvisioningAccountConfig::CONFIG_NAME,
        ApiConfig::CONFIG_NAME,
        LoggingConfig::CONFIG_NAME,
    ];

    /**
     * ConfigurationAccount constructor.
     *
     * @param ProvisioningConfiguration|string|array|null $config
     */
    public function __construct($config = null)
    {
        $this->init($config);
    }

    /**
     * ConfigurationAccount initializer
     *
     * @param ProvisioningConfiguration|string|array|null $config
     */
    public function init($config = null)
    {
        $this->initSections();

        if ($config === null) {
            $config = (string)getenv(self::CLOUDINARY_ACCOUNT_URL_ENV_VAR) ?: null;
        }

        if (ProvisioningConfigUtils::isAccountUrl($config)) {
            $this->importAccountUrl($config);
        } elseif (is_array($config) || JsonUtils::isJsonString($config)) {
            $this->importJson($config);
        } elseif ($config instanceof self) {
            $this->importConfig($config);
        } else {
            throw new ConfigurationException('Invalid account configuration, please set up your environment');
        }
    }

    /**
     * Imports configuration from a account URL.
     *
     * @param string|UriInterface $accountUrl The account URL.
     *
     * @return ProvisioningConfiguration
     */
    public function importAccountUrl($accountUrl)
    {
        $this->importJson(ProvisioningConfigUtils::parseAccountUrl($accountUrl));

        return $this;
    }

    /**
     * This is the actual constructor.
     *
     * @param $json
     *
     * @return ProvisioningConfiguration
     */
    public function importJson($json)
    {
        $json = JsonUtils::decode($json);

        $this->provisioningAccount->importJson($json);
        $this->api->importJson($json);
        $this->logging->importJson($json);

        return $this;
    }

    /**
     * Imports configuration from another instance of the ConfigurationAccount.
     *
     * @param ProvisioningConfiguration $otherConfig The source of the configuration.
     *
     * @return ProvisioningConfiguration
     */
    public function importConfig($otherConfig)
    {
        $this->importJson($otherConfig->jsonSerialize());

        return $this;
    }

    /**
     * Serializes ConfigurationAccount to a json array.
     *
     * @param bool $includeSensitive     Whether to include sensitive keys during serialization.
     * @param bool $includeEmptyKeys     Whether to include keys without values.
     * @param bool $includeEmptySections Whether to include sections without keys with non-empty values.
     *
     * @return mixed data which can be serialized by json_encode.
     */
    public function jsonSerialize($includeSensitive = true, $includeEmptyKeys = false, $includeEmptySections = false)
    {
        $json = [];

        foreach ($this->sections as $section) {
            $section = StringUtils::snakeCaseToCamelCase($section);
            $section = $this->$section->jsonSerialize($includeSensitive, $includeEmptyKeys);
            if (! $includeEmptySections && empty(array_values($section)[0])) {
                continue;
            }
            $json = array_merge($json, $section);
        }

        return $json;
    }

    /**
     * Singleton instance for effective access to global configuration
     *
     * Instance can be optionally initialized with the provided $config (used only on the first call)
     *
     * @param ProvisioningConfiguration|string|array|null $config
     *
     * @return ProvisioningConfiguration
     */
    public static function instance($config = null)
    {
        if (self::$instance !== null) {
            return self::$instance;
        }

        self::$instance = new self($config);

        return self::$instance;
    }

    /**
     * Initializes configuration sections.
     */
    protected function initSections()
    {
        $this->provisioningAccount = new ProvisioningAccountConfig();
        $this->api                 = new ApiConfig();
        $this->logging             = new LoggingConfig();
    }
}
