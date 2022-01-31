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

use Cloudinary\ArrayUtils;
use Cloudinary\ClassUtils;
use Cloudinary\JsonUtils;
use Cloudinary\StringUtils;
use InvalidArgumentException;

/**
 * Class BaseConfigSection
 *
 * A base class for a single configuration section.
 */
abstract class BaseConfigSection implements ConfigurableInterface
{
    /**
     * @var string Placeholder for configuration name, must de defined in each derived class.
     */
    const CONFIG_NAME = 'BASE_CONFIG';

    /**
     * @var array of configuration keys that contain sensitive data that should not be exported (for example api key).
     */
    protected static $sensitiveDataKeys = [];

    /**
     * @var array of configuration key aliases (usually used for deprecated keys backwards compatibility).
     */
    protected static $aliases = [];

    /**
     * @var array of configuration keys that were explicitly set by user. Used to distinguish from default values.
     */
    protected $explicitlySetKeys = [];


    /**
     * BaseConfig constructor.
     *
     * @param      $parameters
     * @param bool $includeSensitive
     */
    public function __construct($parameters = null, $includeSensitive = true)
    {
        $this->importParams($parameters, $includeSensitive);
    }

    /**
     * A getter method for accessing non-public properties.
     *
     * Used for providing default values for not configured parameters.
     *
     * @param string $property Property name to get.
     *
     * @return mixed|null Property value.
     */
    public function __get($property)
    {
        if (! property_exists($this, $property)) {
            trigger_error('Undefined property: ' . static::class . '::$' . $property, E_USER_NOTICE);

            return null;
        }

        if (! isset($this->{$property})) {
            $defaultConstName = 'DEFAULT_' . strtoupper(StringUtils::camelCaseToSnakeCase($property));
            if (defined("static::$defaultConstName")) {
                return constant("static::$defaultConstName");
            }
        }

        return $this->{$property};
    }

    /**
     * A setter method for accessing non-public properties.
     *
     * @param string $name  Property name.
     * @param mixed  $value Property value.
     */
    public function __set($name, $value)
    {
        $this->$name = $value;

        $this->explicitlySetKeys[$name] = true;
    }

    /**
     * A setter method with chaining for accessing non-public properties.
     *
     * @param string $name  Property name.
     * @param mixed  $value Property value.
     *
     * @return $this
     *
     * @internal
     */
    public function setConfig($name, $value)
    {
        $this->__set(StringUtils::snakeCaseToCamelCase($name), $value);

        return $this;
    }

    /**
     * Indicates whether the specified name was explicitly set by user.
     *
     * @param string $name Property name.
     *
     * @return bool
     *
     * @internal
     */
    public function isExplicitlySet($name)
    {
        return ArrayUtils::get($this->explicitlySetKeys, StringUtils::snakeCaseToCamelCase($name), false);
    }

    /**
     * Determines if a property is set.
     *
     * @param string $name The name of the property.
     *
     * @return bool
     */
    public function __isset($name)
    {
        $getter = 'get' . ucfirst($name);
        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        }

        return isset($this->$name);
    }

    /**
     * Imports configuration properties from an array of parameters.
     *
     * @param array $parameters       Configuration section parameters.
     * @param bool  $includeSensitive Whether to include sensitive keys.
     *
     * @return static
     */
    public function importParams($parameters, $includeSensitive = true)
    {
        $validKeys = self::importableKeys(self::exportableKeys($includeSensitive));

        $validParams = ArrayUtils::whitelist($parameters, $validKeys);

        if (empty($validParams)) {
            return $this;
        }

        // set class properties
        foreach ($validParams as $name => $value) {
            $propertyName = StringUtils::snakeCaseToCamelCase(ArrayUtils::get(static::$aliases, $name, $name));
            if (property_exists(static::class, $propertyName)) {
                $this->$propertyName = $value;

                $this->explicitlySetKeys[$propertyName] = true;
            }
        }

        return $this;
    }

    /**
     * Checks whether provided keys are configured.
     *
     * @param array $keys The keys to check.
     *
     * @throws InvalidArgumentException In case not all keys are set.
     */
    public function assertNotEmpty(array $keys)
    {
        foreach ($keys as $key) {
            if (empty($this->$key)) {
                throw new InvalidArgumentException("Must supply $key");
            }
        }
    }

    /**
     * Returns an array of exportable configuration section keys.
     *
     * @param bool $includeSensitive Whether to include sensitive keys.
     *
     * @return array of keys
     */
    protected static function exportableKeys($includeSensitive = true)
    {
        $blacklisted = [static::CONFIG_NAME];
        if (! $includeSensitive) {
            $blacklisted = array_merge($blacklisted, static::$sensitiveDataKeys);
        }

        return array_filter(
            ClassUtils::getConstants(static::class, $blacklisted),
            static function ($key) {
                return ! empty($key) && is_string($key);
            }
        );
    }

    /**
     * Returns an array of importable configuration section keys.
     *
     * @param array $exportableKeys The exportable keys to extend with aliases.
     *
     * @return array of keys
     */
    protected static function importableKeys($exportableKeys)
    {
        return array_merge($exportableKeys, array_keys(static::$aliases));
    }


    /**
     * Instantiates a new config section using json array as a source.
     *
     * @param array $json             Configuration source.
     * @param bool  $includeSensitive Whether to include sensitive keys.
     *
     * @return static brand new instance of the configuration section.
     */
    public static function fromJson($json, $includeSensitive = true)
    {
        $json = JsonUtils::decode($json);

        // If provided nested array, pass only parameters
        if (array_key_exists(static::CONFIG_NAME, $json)) {
            $json = $json[static::CONFIG_NAME];
        }

        return new static($json, $includeSensitive);
    }

    /**
     * Instantiates a new config section using Cloudinary url as a source.
     *
     * @param string $cloudinaryUrl    The Cloudinary url.
     * @param bool   $includeSensitive Whether to include sensitive keys.
     *
     * @return static
     */
    public static function fromCloudinaryUrl($cloudinaryUrl, $includeSensitive = true)
    {
        $config = ConfigUtils::parseCloudinaryUrl($cloudinaryUrl);

        return static::fromJson($config, $includeSensitive);
    }


    /**
     * Imports configuration from a json string or an array as a source.
     *
     * @param string|array $json Configuration json.
     *
     * @return static
     */
    public function importJson($json)
    {
        $json = JsonUtils::decode($json);

        // If provided nested array, pass only parameters
        if (array_key_exists(static::CONFIG_NAME, $json)) {
            $json = $json[static::CONFIG_NAME];
        }

        return $this->importParams($json);
    }


    /**
     * Imports configuration from a Cloudinary url as a source.
     *
     * @param string $cloudinaryUrl The Cloudinary url.
     *
     * @return static
     */
    public function importCloudinaryUrl($cloudinaryUrl)
    {
        $config = ConfigUtils::parseCloudinaryUrl($cloudinaryUrl);

        return $this->importJson($config);
    }

    /**
     * Serialises configuration section to a string representation.
     *
     * @param array $excludedKeys     The keys to exclude from export to string.
     *
     * @return string
     */
    public function toString($excludedKeys = [])
    {
        $sectionJson                      = $this->jsonSerialize();

        if (empty($sectionJson)) {
            return '';
        }

        $sectionJson[static::CONFIG_NAME] = ArrayUtils::blacklist($sectionJson[static::CONFIG_NAME], $excludedKeys);

        return urldecode(http_build_query($sectionJson));
    }


    /**
     * Serialises configuration section to a string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }


    /**
     * Serialises configuration section to a json array.
     *
     * @param bool $includeSensitive     Whether to include sensitive keys during serialisation.
     * @param bool $includeEmptyKeys     Whether to include keys without values.
     *
     * @param bool $includeEmptySections Whether to include sections without keys with non-empty values.
     *
     * @return mixed data which can be serialized by json_encode.
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize($includeSensitive = true, $includeEmptyKeys = false, $includeEmptySections = false)
    {
        $keys = [];
        // set class properties
        foreach (self::exportableKeys($includeSensitive) as $key) {
            $propertyName = StringUtils::snakeCaseToCamelCase($key);
            if (property_exists(static::class, $propertyName)
                && ($includeEmptyKeys || $this->$propertyName !== null)
            ) {
                $keys[$key] = $this->$propertyName;
            }
        }

        if (empty($keys) && ! $includeEmptySections) {
            return [];
        }

        return [static::CONFIG_NAME => $keys];
    }
}
