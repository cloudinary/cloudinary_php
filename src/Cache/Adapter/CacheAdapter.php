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

/**
 * Interface CacheAdapter
 *
 * @api
 */
interface CacheAdapter
{
    /**
     * Gets the value specified by parameters.
     *
     * @param string $publicId       The public ID of the resource.
     * @param string $type           The delivery type.
     * @param string $resourceType   The type of the resource.
     * @param string $transformation The transformation string.
     * @param string $format         The format of the resource.
     *
     * @return mixed|null value, null if not found.
     */
    public function get($publicId, $type, $resourceType, $transformation, $format);

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
    public function set($publicId, $type, $resourceType, $transformation, $format, $value);

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

    public function delete($publicId, $type, $resourceType, $transformation, $format);

    /**
     * Flushes all entries from cache.
     *
     * @return bool true on success or false on failure.
     */
    public function flushAll();
}
