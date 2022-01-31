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

use Cloudinary\Exception\ConfigurationException;
use Cloudinary\JsonUtils;
use Cloudinary\StringUtils;
use Psr\Http\Message\UriInterface;

/**
 * Defines the available global configurations.
 *
 * @api
 */
class Configuration implements ConfigurableInterface
{
    const CLOUDINARY_URL_ENV_VAR = 'CLOUDINARY_URL';

    /**
     * The version of the configuration scheme
     *
     * @var int
     */
    const VERSION = 1;

    /**
     * Main configuration sections
     *
     * @var array
     */
    protected $sections
        = [
            CloudConfig::CONFIG_NAME,
            ApiConfig::CONFIG_NAME,
            UrlConfig::CONFIG_NAME,
            TagConfig::CONFIG_NAME,
            ResponsiveBreakpointsConfig::CONFIG_NAME,
            AuthTokenConfig::CONFIG_NAME,
            LoggingConfig::CONFIG_NAME,
        ];

    /**
     * @var bool Indicates whether to include sensitive keys during serialisation to string/json.
     */
    protected $includeSensitive;

    /**
     * @var static Singleton instance for the Configuration.
     */
    private static $instance;

    /**
     * The configuration of the cloud.
     *
     * @var CloudConfig $cloud
     */
    public $cloud;

    /**
     * The configuration of the API.
     *
     * @var ApiConfig $api
     */
    public $api;

    /**
     * The configuration of the URL.
     *
     * @var UrlConfig $url
     */
    public $url;

    /**
     * The configuration of tags.
     *
     * @var TagConfig $tag
     */
    public $tag;

    /**
     * The configuration of the responsive breakpoints cache.
     *
     * @var ResponsiveBreakpointsConfig $responsiveBreakpoints
     */
    public $responsiveBreakpoints;

    /**
     * The authentication token.
     *
     * @var AuthTokenConfig $authToken
     */
    public $authToken;

    /**
     * The configuration of the logging.
     *
     * @var LoggingConfig $logging
     */
    public $logging;

    /**
     * Configuration constructor.
     *
     * @param Configuration|string|array|null $config           Configuration source. Can be Cloudinary url, json,
     *                                                          array, another instance of the configuration.
     * @param bool                            $includeSensitive Indicates whether to include sensitive keys during
     *                                                          serialisation to string/json.
     */
    public function __construct($config = null, $includeSensitive = true)
    {
        $this->init($config, $includeSensitive);
    }

    /**
     * Configuration initializer.
     *
     * Used for initialising and resetting config
     *
     * @param Configuration|string|array|null $config           Configuration source. Can be Cloudinary url, json,
     *                                                          array, another instance of the configuration.
     * @param bool                            $includeSensitive Indicates whether to include sensitive keys during
     *                                                          serialisation to string/json.
     */
    public function init($config = null, $includeSensitive = true)
    {
        $this->includeSensitive = $includeSensitive;

        $this->initSections();

        $this->import($config);
    }

    /**
     * Initializes configuration sections.
     */
    protected function initSections()
    {
        $this->cloud                 = new CloudConfig();
        $this->api                   = new ApiConfig();
        $this->url                   = new UrlConfig();
        $this->tag                   = new TagConfig();
        $this->responsiveBreakpoints = new ResponsiveBreakpointsConfig();
        $this->authToken             = new AuthTokenConfig();
        $this->logging               = new LoggingConfig();
    }

    /**
     * Imports configuration.
     *
     * @param Configuration|string|array|null $config Configuration source. Can be Cloudinary url, json, array, another
     *                                                instance of the configuration.
     */
    public function import($config = null)
    {
        if ($config === null) {
            if (! getenv(self::CLOUDINARY_URL_ENV_VAR)) {
                // Nothing to import
                return;
            }
            // When no configuration provided, fallback to the environment variable.
            $config = (string)getenv(self::CLOUDINARY_URL_ENV_VAR);
        }

        if (ConfigUtils::isCloudinaryUrl($config)) {
            $this->importCloudinaryUrl($config);
        } elseif (is_array($config) || JsonUtils::isJsonString($config)) {
            $this->importJson($config);
        } elseif ($config instanceof self) {
            $this->importConfig($config);
        } else {
            throw new ConfigurationException('Invalid configuration, please set up your environment');
        }
    }

    /**
     * Singleton instance for effective access to global configuration.
     *
     * Instance can be optionally initialised with the provided $config (used only on the first call).
     *
     * @param Configuration|string|array|null $config           Configuration source. Can be Cloudinary url, json,
     *                                                          array, another instance of the configuration.
     *
     * @return Configuration
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
     * Creates Configuration using json string or array as a source.
     *
     * @param string|array $json Configuration json.
     *
     * @return static
     */
    public static function fromJson($json)
    {
        return new self($json);
    }

    /**
     * Creates Configuration using an array of parameters as a source.
     *
     * @param array $params Configuration parameters.
     *
     * @return static
     */
    public static function fromParams($params)
    {
        return new self($params);
    }

    /**
     * Creates Configuration using Cloudinary url as a source.
     *
     * @param string $cloudinaryUrl The Cloudinary url.
     *
     * @return static
     */
    public static function fromCloudinaryUrl($cloudinaryUrl)
    {
        return new self($cloudinaryUrl);
    }

    /**
     * This is the actual constructor.
     *
     * @param $json
     *
     * @return Configuration
     */
    public function importJson($json)
    {
        $json = JsonUtils::decode($json);

        $this->cloud->importJson($json);
        $this->api->importJson($json);
        $this->url->importJson($json);
        $this->tag->importJson($json);
        $this->responsiveBreakpoints->importJson($json);
        $this->authToken->importJson($json);
        $this->logging->importJson($json);

        return $this;
    }

    /**
     * Imports configuration from a cloudinary URL.
     *
     * @param string|UriInterface $cloudinaryUrl The cloudinary URL.
     *
     * @return Configuration
     */
    public function importCloudinaryUrl($cloudinaryUrl)
    {
        $this->importJson(ConfigUtils::parseCloudinaryUrl($cloudinaryUrl));

        return $this;
    }

    /**
     * Imports configuration from another instance of the Configuration.
     *
     * @param Configuration $otherConfig The source of the configuration.
     *
     * @return Configuration
     */
    public function importConfig($otherConfig)
    {
        $this->importJson($otherConfig->jsonSerialize());

        return $this;
    }

    public function validate()
    {
        if (empty($this->cloud->cloudName)) {
            throw new ConfigurationException('Invalid configuration, please set up your environment');
        }
    }

    /**
     * Serialises Configuration to Cloudinary url
     *
     * @return string Resulting Cloudinary url
     */
    public function toString()
    {
        $url = ConfigUtils::buildCloudinaryUrl($this->jsonSerialize());

        $sections = [];
        foreach ($this->sections as $section) {
            $section     = StringUtils::snakeCaseToCamelCase($section);
            $sections [] = (string)($this->$section);
        }

        $url = implode('?', array_filter([$url, implode('&', array_filter($sections))]));

        return $url;
    }

    /**
     * Serialises Configuration to Cloudinary url
     *
     * @return string Resulting Cloudinary url
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Serialises Configuration to a json array.
     *
     * @param bool $includeSensitive     Whether to include sensitive keys during serialisation.
     * @param bool $includeEmptyKeys     Whether to include keys without values.
     * @param bool $includeEmptySections Whether to include sections without keys with non-empty values.
     *
     * @return mixed data which can be serialized by json_encode.
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize($includeSensitive = true, $includeEmptyKeys = false, $includeEmptySections = false)
    {
        $json = ['version' => self::VERSION];

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
}
