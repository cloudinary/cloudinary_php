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
    public const CLOUDINARY_ACCOUNT_URL_ENV_VAR = 'CLOUDINARY_ACCOUNT_URL';

    /**
     * @var ?static Singleton instance for the ConfigurationAccount
     */
    private static ?ProvisioningConfiguration $instance = null;

    public ?ProvisioningAccountConfig $provisioningAccount = null;

    /**
     * @var ApiConfig $api The configuration of the API.
     */
    public ApiConfig $api;

    public LoggingConfig $logging;

    /**
     * @var array Main configuration sections
     */
    protected array $sections
        = [
            ProvisioningAccountConfig::CONFIG_NAME,
            ApiConfig::CONFIG_NAME,
            LoggingConfig::CONFIG_NAME,
        ];

    /**
     * ConfigurationAccount constructor.
     *
     */
    public function __construct(array|string|ProvisioningConfiguration|null $config = null)
    {
        $this->init($config);
    }

    /**
     * ConfigurationAccount initializer
     *
     */
    public function init(array|string|ProvisioningConfiguration|null $config = null): void
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
     */
    public function importAccountUrl(UriInterface|string $accountUrl): static
    {
        $this->importJson(ProvisioningConfigUtils::parseAccountUrl($accountUrl));

        return $this;
    }

    /**
     * This is the actual constructor.
     *
     *
     */
    public function importJson($json): static
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
     */
    public function importConfig(ProvisioningConfiguration $otherConfig): static
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
     * @return array data which can be serialized by json_encode.
     */
    public function jsonSerialize(
        bool $includeSensitive = true,
        bool $includeEmptyKeys = false,
        bool $includeEmptySections = false
    ): array {
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
     *
     * @return ProvisioningConfiguration Provisioning Configuration
     */
    public static function instance(array|string|ProvisioningConfiguration|null $config = null
    ): ProvisioningConfiguration {
        if (self::$instance !== null) {
            return self::$instance;
        }

        self::$instance = new self($config);

        return self::$instance;
    }

    /**
     * Initializes configuration sections.
     */
    protected function initSections(): void
    {
        $this->provisioningAccount = new ProvisioningAccountConfig();
        $this->api                 = new ApiConfig();
        $this->logging             = new LoggingConfig();
    }
}
