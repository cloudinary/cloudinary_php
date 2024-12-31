<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Asset;

use BadMethodCallException;
use Cloudinary\ArrayUtils;
use Cloudinary\ClassUtils;
use Cloudinary\Configuration\AssetConfigTrait;
use Cloudinary\Configuration\CloudConfig;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Configuration\LoggingConfig;
use Cloudinary\Configuration\UrlConfig;
use Cloudinary\Exception\ConfigurationException;
use Cloudinary\JsonUtils;
use Cloudinary\Log\LoggerTrait;
use Cloudinary\StringUtils;
use Psr\Http\Message\UriInterface;

/**
 * Class BaseAsset
 *
 * @api
 */
abstract class BaseAsset implements AssetInterface
{
    /**
     * @var string $assetType The type of the asset.
     */
    protected static string $assetType;

    use AssetDescriptorTrait;
    use AssetConfigTrait;

    use DeliveryTypeTrait;
    use LoggerTrait;
    use AssetFinalizerTrait;

    // Asset Configuration

    /**
     * @var CloudConfig $cloud The configuration of the cloud.
     */
    public CloudConfig $cloud;

    /**
     * @var UrlConfig $urlConfig The configuration of the URL.
     */
    public UrlConfig $urlConfig;

    /**
     * @var AssetDescriptor $asset The asset descriptor.
     */
    public $asset;

    /**
     * @var AuthToken $authToken The authentication token.
     */
    public $authToken;

    /**
     * @var array A list of the delivery types that support SEO suffix.
     */
    protected static array $suffixSupportedDeliveryTypes = [];

    /**
     * BaseAsset constructor.
     *
     * @param mixed      $source        The asset source.
     * @param mixed|null $configuration Configuration.
     */
    public function __construct(mixed $source, mixed $configuration = null)
    {
        if ($source instanceof $this) { // copy constructor
            $this->deepCopy($source);

            return;
        }

        if ($source instanceof self) { // here comes conversion
            // TODO: discuss configuration transfer
            $this->asset = clone $source->asset;

            $this->cloud     = clone $source->cloud;
            $this->urlConfig = clone $source->urlConfig;
            $this->authToken = clone $source->authToken;
            $this->logging   = clone $source->logging;

            return;
        }

        if ($source instanceof AssetDescriptor) {
            $this->asset = clone $source;
        } else {
            $assetType   = static::getAssetType($this);
            $this->asset = new AssetDescriptor($source, $assetType);
        }

        if ($configuration === null) {
            $configuration = Configuration::instance(); // get global instance
        }

        $this->configuration($configuration);

        $this->authToken = new AuthToken($configuration);
    }

    /**
     * Internal method that returns the asset type of the current object.
     *
     * @param string|self $class The instance of the object.
     *
     * @internal
     */
    protected static function getAssetType(BaseAsset|string $class): string
    {
        if (isset(static::$assetType)) {
            return static::$assetType;
        }

        if (! is_string($class)) {
            $class = ClassUtils::getClassName($class);
        }

        return StringUtils::camelCaseToSnakeCase(ClassUtils::getBaseName($class));
    }

    /**
     * Internal getter for a list of the delivery types that support SEO suffix.
     *
     *
     * @internal
     */
    public static function getSuffixSupportedDeliveryTypes(): array
    {
        return static::$suffixSupportedDeliveryTypes;
    }

    /**
     * Performs a deep copy from another asset.
     *
     * @param static $other The source asset.
     *
     * @internal
     */
    public function deepCopy(BaseAsset $other): void
    {
        $this->cloud     = clone $other->cloud;
        $this->urlConfig = clone $other->urlConfig;
        $this->asset     = clone $other->asset;
        $this->authToken = clone $other->authToken;
        $this->logging   = clone $other->logging;
    }


    /**
     * Creates a new asset from the provided string (URL).
     *
     * @param string $string The asset string (URL).
     */
    public static function fromString(string $string): static
    {
        //TODO: Parse URL and populate the asset
        throw new BadMethodCallException('Not Implemented');
    }


    /**
     * Creates a new asset from the provided JSON.
     *
     * @param array|string $json The asset json. Can be an array or a JSON string.
     *
     */
    public static function fromJson(array|string $json): static
    {
        //TODO: Parse URL and populate the asset
        throw new BadMethodCallException('Not Implemented');
    }


    /**
     * Creates a new asset from the provided source and an array of parameters.
     *
     * @param string $source The public ID of the asset.
     * @param array  $params The asset parameters.
     *
     */
    public static function fromParams(string $source, array $params): static
    {
        ArrayUtils::setDefaultValue($params, 'resource_type', static::getAssetType(static::class));

        $params['public_id'] = $source;

        $asset         = AssetDescriptor::fromParams($source, $params);
        $configuration = new Configuration(Configuration::instance());

        # set v1 defaults
        if (! $configuration->url->isExplicitlySet('secure')) {
            $configuration->url->secure(false);
        }
        if (! $configuration->url->isExplicitlySet('analytics')) {
            $configuration->url->analytics(false);
        }

        $configuration->importJson($params);
        $configuration->validate();

        return new static($asset, $configuration);
    }

    /**
     * Imports data from the provided string (URL).
     *
     * @param string $string The asset string (URL).
     */
    public function importString(string $string): static
    {
        throw new BadMethodCallException('Import string is not implemented');
    }


    /**
     * Imports data from the provided JSON.
     *
     * @param array|string $json The asset json. Can be an array or a JSON string.
     *
     */
    public function importJson(array|string $json): static
    {
        try {
            $json = JsonUtils::decode($json);

            $this->cloud     = CloudConfig::fromJson($json);
            $this->urlConfig = UrlConfig::fromJson($json);
            $this->asset     = AssetDescriptor::fromJson($json);
            $this->authToken = AuthToken::fromJson($json);
            $this->logging   = LoggingConfig::fromJson($json);
        } catch (\InvalidArgumentException $e) {
            $this->getLogger()->critical(
                'Error importing JSON',
                [
                    'exception' => $e->getMessage(),
                    'json'      => json_encode($json),
                    'file'      => $e->getFile(),
                    'line'      => $e->getLine(),
                ]
            );
            throw $e;
        }

        return $this;
    }

    /**
     * Sets the asset configuration.
     *
     * @param array|Configuration $configuration The configuration source.
     *
     */
    public function configuration(Configuration|array $configuration): static
    {
        $tempConfiguration = new Configuration($configuration, true); // TODO: improve performance here
        $this->cloud       = $tempConfiguration->cloud;
        $this->urlConfig   = $tempConfiguration->url;
        $this->logging     = $tempConfiguration->logging;

        return $this;
    }

    /**
     * Imports (merges) the asset configuration.
     *
     * @param array|Configuration $configuration The configuration source.
     *
     */
    public function importConfiguration(Configuration|array $configuration): static
    {
        $this->cloud->importJson($configuration->cloud->jsonSerialize());
        $this->urlConfig->importJson($configuration->url->jsonSerialize());
        $this->logging->importJson($configuration->logging->jsonSerialize());

        return $this;
    }


    /**
     * Returns the public ID of the asset.
     *
     * @param bool $omitExtension Indicates whether to exclude the file extension.
     *
     */
    public function getPublicId(bool $omitExtension = false): string
    {
        return $this->asset->publicId($omitExtension);
    }

    /**
     * Sets the public ID of the asset.
     *
     * @param string $publicId The public ID.
     *
     */
    public function setPublicId(string $publicId): static
    {
        $this->asset->setPublicId($publicId);

        return $this;
    }

    /**
     * Internal pre-serialization helper.
     *
     *
     * @internal
     */
    protected function prepareUrlParts(): array
    {
        return [
            'distribution' => $this->finalizeDistribution(),
            'asset_type'   => $this->finalizeAssetType(),
            'signature'    => $this->finalizeSimpleSignature(),
            'version'      => $this->finalizeVersion(),
            'source'       => $this->finalizeSource(),
        ];
    }

    /**
     * Serializes to URL string.
     *
     * @throws ConfigurationException
     */
    public function toUrl(): UriInterface
    {
        return $this->finalizeUrl(ArrayUtils::implodeUrl($this->prepareUrlParts()));
    }


    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        try {
            return (string)$this->toUrl();
        } catch (\Exception $e) {
            trigger_error($e->getMessage(), E_USER_ERROR);
        }
    }

    /**
     * Serialized to JSON.
     *
     * @param bool $includeEmptyKeys     Whether to include empty keys.
     * @param bool $includeEmptySections Whether to include empty sections.
     *
     */
    public function jsonSerialize(bool $includeEmptyKeys = false, bool $includeEmptySections = false): array
    {
        $json = $this->asset->jsonSerialize($includeEmptyKeys);

        foreach ([$this->cloud, $this->urlConfig] as $confSection) {
            $section = $confSection->jsonSerialize(false, $includeEmptyKeys, $includeEmptySections);
            if (! $includeEmptySections && empty(array_values($section)[0])) {
                continue;
            }
            $json = array_merge($json, $section);
        }

        return $json;
    }

    /**
     * Sets the property of the asset descriptor.
     *
     * @param string $propertyName  The name of the property.
     * @param mixed  $propertyValue The value of the property.
     *
     * @return $this
     *
     * @internal
     */
    public function setAssetProperty(string $propertyName, mixed $propertyValue): static
    {
        try {
            $this->asset->setAssetProperty($propertyName, $propertyValue);
        } catch (\UnexpectedValueException $e) {
            $this->getLogger()->critical(
                $e->getMessage(),
                [
                    'propertyName'  => $propertyName,
                    'propertyValue' => $propertyValue,
                    'file'          => $e->getFile(),
                    'line'          => $e->getLine(),
                ]
            );
            throw $e;
        }

        return $this;
    }

    /**
     * Sets the Account configuration key with the specified value.
     *
     * @param string $configKey   The configuration key.
     * @param mixed  $configValue THe configuration value.
     *
     * @return $this
     *
     * @internal
     */
    public function setCloudConfig(string $configKey, mixed $configValue): static
    {
        $this->cloud->setCloudConfig($configKey, $configValue);

        return $this;
    }

    /**
     * Sets the Url configuration key with the specified value.
     *
     * @param string $configKey   The configuration key.
     * @param mixed  $configValue THe configuration value.
     *
     * @return $this
     *
     * @internal
     */
    public function setUrlConfig(string $configKey, mixed $configValue): static
    {
        $this->urlConfig->setUrlConfig($configKey, $configValue);

        return $this;
    }
}
