<?php


namespace Cloudinary\Cache\Adapter;

use Cloudinary\Cache\Storage\KeyValueStorage;

/**
 * Class KeyValueCacheAdapter
 * @package Cloudinary\Cache\Adapter
 */
class KeyValueCacheAdapter implements CacheAdapter
{
    /**
     * @implements KeyValueStorageInterface
     */
    private $keyValueStorage = null;

    /**
     * KeyValueCacheAdapter constructor.
     *
     * @param $storage
     */
    public function __construct($storage)
    {
        $this->setKeyValueStorage($storage);
    }

    /**
     * @param object $storage PSR-16 compliant cache
     *
     * @return bool
     */
    private function setKeyValueStorage($storage)
    {
        if (is_null($storage) || ! $storage instanceof KeyValueStorage) {
            return false;
        }

        $this->keyValueStorage = $storage;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function get($publicId, $type, $resourceType, $transformation, $format)
    {
        if (is_null($this->keyValueStorage)) {
            return null;
        }

        return json_decode(
            $this->keyValueStorage->get(
                self::generateCacheKey($publicId, $type, $resourceType, $transformation, $format)
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function set($publicId, $type, $resourceType, $transformation, $format, $value)
    {
        if (is_null($this->keyValueStorage)) {
            return null;
        }

        return $this->keyValueStorage->set(
            self::generateCacheKey($publicId, $type, $resourceType, $transformation, $format),
            json_encode($value)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function delete($publicId, $type, $resourceType, $transformation, $format)
    {
        if (is_null($this->keyValueStorage)) {
            return null;
        }

        return $this->keyValueStorage->delete(
            self::generateCacheKey($publicId, $type, $resourceType, $transformation, $format)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function flushAll()
    {
        if (is_null($this->keyValueStorage)) {
            return null;
        }

        return $this->keyValueStorage->clear();
    }

    /**
     * Generates key-value storage key from parameters
     *
     * @param $publicId
     * @param $type
     * @param $resourceType
     * @param $transformation
     * @param $format
     *
     * @return string
     */
    public static function generateCacheKey($publicId, $type, $resourceType, $transformation, $format)
    {
        return sha1(implode("/", array_filter([$publicId, $type, $resourceType, $transformation, $format])));
    }
}
