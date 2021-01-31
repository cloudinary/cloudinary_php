<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Cache\Adapter;

use Cloudinary\ArrayUtils;
use Cloudinary\Cache\Storage\KeyValueStorage;
use InvalidArgumentException;
use Psr\SimpleCache\CacheInterface;

/**
 * A cache adapter for a key-value storage type
 *
 * @api
 */
class KeyValueCacheAdapter implements CacheAdapter
{
    /**
     * The storage interface.
     *
     * @var KeyValueStorage
     */
    private $keyValueStorage;

    /**
     * Create a new adapter for the provided storage interface.
     *
     * @param KeyValueStorage $storage A key-value storage interface.
     */
    public function __construct($storage)
    {
        $this->setKeyValueStorage($storage);
    }

    /**
     * Set the storage interface.
     *
     * @param object $storage \Cloudinary\Cache\Storage\KeyValueStorage or PSR-16 compliant cache.
     *
     * @return bool true if successful.
     */
    private function setKeyValueStorage($storage)
    {
        if (! is_object($storage)) {
            throw new InvalidArgumentException('An instance of valid storage must be provided');
        }

        $storageClasses = class_implements($storage);
        $validStorages  = [KeyValueStorage::class, CacheInterface::class];

        $found = count(ArrayUtils::whitelist($storageClasses, $validStorages)) > 0;

        if (! $found) {
            throw new InvalidArgumentException('An instance of valid storage must be provided');
        }

        $this->keyValueStorage = $storage;

        return true;
    }

    /**
     * Gets value specified by parameters.
     *
     * @param string $publicId       The public ID of the resource.
     * @param string $type           The delivery type.
     * @param string $resourceType   The type of the resource.
     * @param string $transformation The transformation string.
     * @param string $format         The format of the resource.
     *
     * @return mixed|null value, null if not found.
     */
    public function get($publicId, $type, $resourceType, $transformation, $format)
    {
        return json_decode(
            $this->keyValueStorage->get(
                self::generateCacheKey($publicId, $type, $resourceType, $transformation, $format)
            ),
            false
        );
    }

    /**
     * Sets the value specified by parameters.
     *
     * @param string $publicId       The public ID of the resource.
     * @param string $type           The delivery type.
     * @param string $resourceType   The type of the resource.
     * @param string $transformation The transformation string.
     * @param string $format         The format of the resource.
     * @param mixed  $value          The value to set.
     *
     * @return bool true on success or false on failure.
     */
    public function set($publicId, $type, $resourceType, $transformation, $format, $value)
    {
        return $this->keyValueStorage->set(
            self::generateCacheKey($publicId, $type, $resourceType, $transformation, $format),
            json_encode($value)
        );
    }

    /**
     * Deletes the entry specified by parameters.
     *
     * @param string $publicId       The public ID of the resource.
     * @param string $type           The delivery type.
     * @param string $resourceType   The type of the resource.
     * @param string $transformation The transformation string.
     * @param string $format         The format of the resource.
     *
     * @return bool true on success or false on failure.
     */
    public function delete($publicId, $type, $resourceType, $transformation, $format)
    {
        return $this->keyValueStorage->delete(
            self::generateCacheKey($publicId, $type, $resourceType, $transformation, $format)
        );
    }

    /**
     * Flushes all entries from cache.
     *
     * @return bool true on success or false on failure.
     */
    public function flushAll()
    {
        return $this->keyValueStorage->clear();
    }

    /**
     * Generates key-value storage key from parameters.
     *
     * @param string $publicId       The public ID of the resource.
     * @param string $type           The delivery type.
     * @param string $resourceType   The type of the resource.
     * @param string $transformation The transformation string.
     * @param string $format         The format of the resource.
     *
     * @return string Resulting cache key.
     */
    public static function generateCacheKey($publicId, $type, $resourceType, $transformation, $format)
    {
        return sha1(ArrayUtils::implodeUrl([$publicId, $type, $resourceType, $transformation, $format]));
    }
}
