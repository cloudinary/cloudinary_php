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
    protected const CONFIG_NAME = 'BASE_CONFIG';

    /**
     * @var array of configuration keys that contain sensitive data that should not be exported (for example api key).
     */
    protected static array $sensitiveDataKeys = [];

    /**
     * @var array of configuration key aliases (usually used for deprecated keys backwards compatibility).
     */
    protected static array $aliases = [];

    /**
     * @var array of configuration keys that were explicitly set by user. Used to distinguish from default values.
     */
    protected array $explicitlySetKeys = [];


    /**
     * BaseConfig constructor.
     *
     */
    public function __construct($parameters = null, bool $includeSensitive = true)
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
    public function __get(string $property)
    {
        if (! property_exists($this, $property)) {
            trigger_error('Undefined property: ' . static::class . '::$' . $property);

            return null;
        }

        if (! isset($this->{$property})) {
            $defaultConstName = 'DEFAULT_' . strtoupper(StringUtils::camelCaseToSnakeCase($property));
            if (defined("static::$defaultConstName")) {
                return constant("static::$defaultConstName");
            }
        }

        return isset($this->{$property}) ? $this->{$property} : null;
    }

    /**
     * A setter method for accessing non-public properties.
     *
     * @param string $name  Property name.
     * @param mixed  $value Property value.
     */
    public function __set(string $name, mixed $value)
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
    public function setConfig(string $name, mixed $value): static
    {
        $this->__set(StringUtils::snakeCaseToCamelCase($name), $value);

        return $this;
    }

    /**
     * Indicates whether the specified name was explicitly set by user.
     *
     * @param string $name Property name.
     *
     *
     * @internal
     */
    public function isExplicitlySet(string $name): bool
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
    public function __isset(string $name)
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
     * @param ?array $parameters       Configuration section parameters.
     * @param bool   $includeSensitive Whether to include sensitive keys.
     *
     */
    public function importParams(?array $parameters, bool $includeSensitive = true): static
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
    public function assertNotEmpty(array $keys): void
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
    protected static function exportableKeys(bool $includeSensitive = true): array
    {
        $blacklisted = [static::CONFIG_NAME];
        if (! $includeSensitive) {
            $blacklisted = array_merge($blacklisted, static::$sensitiveDataKeys);
        }

        return array_filter(
            ClassUtils::getConstants(static::class, $blacklisted),
            static fn($key) => ! empty($key) && is_string($key)
        );
    }

    /**
     * Returns an array of importable configuration section keys.
     *
     * @param array $exportableKeys The exportable keys to extend with aliases.
     *
     * @return array of keys
     */
    protected static function importableKeys(array $exportableKeys): array
    {
        return array_merge($exportableKeys, array_keys(static::$aliases));
    }


    /**
     * Instantiates a new config section using json array as a source.
     *
     * @param array|string $json Configuration source.
     *
     * @return static brand-new instance of the configuration section.
     */
    public static function fromJson(array|string $json): static
    {
        $json = JsonUtils::decode($json);

        // If provided nested array, pass only parameters
        if (array_key_exists(static::CONFIG_NAME, $json)) {
            $json = $json[static::CONFIG_NAME];
        }

        return new static($json);
    }

    /**
     * Instantiates a new config section using Cloudinary url as a source.
     *
     * @param string $cloudinaryUrl The Cloudinary url.
     *
     */
    public static function fromCloudinaryUrl(string $cloudinaryUrl): static
    {
        $config = ConfigUtils::parseCloudinaryUrl($cloudinaryUrl);

        return static::fromJson($config);
    }


    /**
     * Imports configuration from a json string or an array as a source.
     *
     * @param array|string $json Configuration json.
     *
     */
    public function importJson(array|string $json): static
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
     */
    public function importCloudinaryUrl(string $cloudinaryUrl): static
    {
        $config = ConfigUtils::parseCloudinaryUrl($cloudinaryUrl);

        return $this->importJson($config);
    }

    /**
     * Serialises configuration section to a string representation.
     *
     * @param array $excludedKeys The keys to exclude from export to string.
     *
     */
    public function toString(array $excludedKeys = []): string
    {
        $sectionJson = $this->jsonSerialize();

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
     * @return array data which can be serialized by json_encode.
     */
    public function jsonSerialize(
        bool $includeSensitive = true,
        bool $includeEmptyKeys = false,
        bool $includeEmptySections = false
    ): array {
        $keys = [];
        // set class properties
        foreach (self::exportableKeys($includeSensitive) as $key) {
            $propertyName = StringUtils::snakeCaseToCamelCase($key);
            if (property_exists(static::class, $propertyName)
                && ($includeEmptyKeys || isset($this->$propertyName))
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
