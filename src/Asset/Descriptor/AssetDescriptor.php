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

use Cloudinary\ArrayUtils;
use Cloudinary\FileUtils;
use Cloudinary\JsonUtils;

/**
 * Class AssetDescriptor
 *
 * @property string suffix SEO URL suffix
 *
 * @api
 */
class AssetDescriptor implements AssetInterface
{
    /**
     * @var string $assetType The type of the asset, A.K.A resource type.
     *                        Use the constants defined in the AssetType class.
     * @see AssetType
     */
    public $assetType = AssetType::IMAGE;
    /**
     * @var string $deliveryType The delivery type of the asset, A.K.A type.
     *                           Use the constants defined in the DeliveryType class.
     * @see DeliveryType
     */
    public $deliveryType = DeliveryType::UPLOAD; // A.K.A type
    /**
     * @var int|string $version Asset version, typically set to unix timestamp.
     */
    public $version;
    /**
     * @var string $location Can be directory, URL(including path, excluding filename), etc.
     */
    public $location;
    /**
     * @var string $filename Basename without extension.
     */
    public $filename;
    /**
     * @var string $extension A.K.A format.
     */
    public $extension;

    /**
     * @var string $suffix SEO URL suffix.
     */
    private $suffix;

    /**
     * AssetDescriptor constructor.
     *
     * @param string $publicId  The public ID of the asset.
     * @param string $assetType The type of the asset.
     */
    public function __construct($publicId, $assetType = AssetType::IMAGE)
    {
        $this->setPublicId($publicId);
        $this->assetType = $assetType;
    }

    /**
     * Gets inaccessible class property by name.
     *
     * @param string $name The name of the property.
     *
     * @return mixed|null
     */
    public function __get($name)
    {
        if ($name === 'suffix') {
            return $this->suffix;
        }

        trigger_error('Undefined property: ' . static::class . '::$' . $name, E_USER_NOTICE);

        return null;
    }

    /**
     * Indicates whether the inaccessible class property is set.
     *
     * @param string $key The class property name.
     *
     * @return bool
     */
    public function __isset($key)
    {
        try {
            if (null === $this->__get($key)) {
                return false;
            }
        } catch (\Exception $e) { // Undefined property
            return false;
        }

        return true;
    }

    /**
     * Sets the inaccessible class property.
     *
     * @param string $name  The class property name.
     * @param mixed  $value The class property value.
     */
    public function __set($name, $value)
    {
        $this->setAssetProperty($name, $value);
    }

    /**
     * Sets the public ID of the asset.
     *
     * @param string $publicId The public ID of the asset.
     *
     * @return $this
     */
    public function setPublicId($publicId)
    {
        list($this->location, $this->filename, $this->extension) = FileUtils::splitPathFilenameExtension($publicId);

        return $this;
    }

    /**
     * Gets the public ID of the asset
     *
     * @param bool $noExtension When true, omits file extension.
     *
     * @return string
     */
    public function publicId($noExtension = false)
    {
        return ArrayUtils::implodeFiltered(
            '.',
            [
                ArrayUtils::implodeFiltered('/', [$this->location, $this->filename]),
                $noExtension ? '' : $this->extension,
            ]
        );
    }

    /**
     * Sets the URL SEO suffix of the asset.
     *
     * @param string $suffix The SEO suffix.
     *
     * @return $this
     */
    public function setSuffix($suffix)
    {
        if (is_null($suffix)) {
            return $this;
        }

        if (preg_match('/[.\/]/', $suffix)) {
            throw new \UnexpectedValueException(static::class . '::$suffix must not include . or /');
        }

        $this->suffix = $suffix;

        return $this;
    }

    /**
     * Creates a new asset from the provided string (URL).
     *
     * @param string $string The asset string (URL).
     *
     * @return mixed
     */
    public static function fromString($string)
    {
        throw new \BadMethodCallException('Not Implemented');
    }

    /**
     * Creates a new asset from the provided JSON.
     *
     * @param string|array $json The asset json. Can be an array or a JSON string.
     *
     * @return mixed
     */
    public static function fromJson($json)
    {
        $new = new self('');

        $new->importJson($json);

        return $new;
    }

    /**
     * Creates a new asset from the provided source and an array of (legacy) parameters.
     *
     * @param string $source The public ID of the asset.
     * @param array  $params The asset parameters.
     *
     * @return mixed
     */
    public static function fromParams($source, $params = [])
    {
        $assetJson = [
            'asset_type'    => ArrayUtils::get($params, 'resource_type', AssetType::IMAGE),
            'delivery_type' => ArrayUtils::get($params, 'type', DeliveryType::UPLOAD),
            'version'       => ArrayUtils::get($params, 'version'),
            'suffix'        => ArrayUtils::get($params, 'url_suffix'),
        ];

        list(
            $assetJson['location'],
            $assetJson['filename'],
            $assetJson['extension']
            )
            = FileUtils::splitPathFilenameExtension($source);

        // Explicit 'format' parameter overrides extension. (Fetch URLs are not affected).
        if ($assetJson['delivery_type'] != DeliveryType::FETCH) {
            ArrayUtils::addNonEmpty($assetJson, 'extension', ArrayUtils::get($params, 'format'));
        }

        return self::fromJson(['asset' => $assetJson]);
    }

    /**
     * Imports data from the provided string (URL).
     *
     * @param string $string The asset string (URL).
     *
     * @return mixed
     */
    public function importString($string)
    {
        throw new \BadMethodCallException('Not Implemented');
    }


    /**
     * Imports data from the provided JSON.
     *
     * @param string|array $json The asset json. Can be an array or a JSON string.
     *
     * @return AssetDescriptor
     */
    public function importJson($json)
    {
        $json = JsonUtils::decode($json);

        if (! array_key_exists('asset', $json) || ! array_key_exists('filename', $json['asset'])) {
            throw new \InvalidArgumentException('Invalid asset JSON');
        }

        $assetJson = $json['asset'];

        $this->assetType    = ArrayUtils::get($assetJson, 'asset_type', AssetType::IMAGE);
        $this->deliveryType = ArrayUtils::get($assetJson, 'delivery_type', DeliveryType::UPLOAD);
        $this->version      = ArrayUtils::get($assetJson, 'version');
        $this->location     = ArrayUtils::get($assetJson, 'location');
        $this->filename     = ArrayUtils::get($assetJson, 'filename');
        $this->extension    = ArrayUtils::get($assetJson, 'extension');

        $this->setSuffix(ArrayUtils::get($assetJson, 'suffix'));

        return $this;
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        return ArrayUtils::implodeUrl(array_values($this->jsonSerialize()));
    }


    /**
     * Serializes to json.
     *
     * @param bool $includeEmptyKeys Whether to include empty keys.
     *
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize($includeEmptyKeys = false)
    {
        $dataArr = [
            'asset_type'    => $this->assetType,
            'delivery_type' => $this->deliveryType,
            'version'       => $this->version,
            'location'      => $this->location,
            'filename'      => $this->filename,
            'extension'     => $this->extension,
            'suffix'        => $this->suffix,
        ];

        if (! $includeEmptyKeys) {
            $dataArr = array_filter($dataArr);
        }

        return ['asset' => $dataArr];
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
    public function setAssetProperty($propertyName, $propertyValue)
    {
        /** @noinspection DegradedSwitchInspection */
        switch ($propertyName) {
            case 'suffix':
                $this->setSuffix($propertyValue);
                break;
            default:
                $this->$propertyName = $propertyValue;
        }

        return $this;
    }
}
