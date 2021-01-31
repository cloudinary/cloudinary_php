<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Cache\Storage;

use Cloudinary\Cache\Storage\KeyValueStorage;

/**
 * Class DummyCacheConnector
 */
class DummyCacheStorage implements KeyValueStorage
{
    private static $dummyCache = [];

    /**
     * @inheritDoc
     */
    public function get($key)
    {
        return array_key_exists($key, self::$dummyCache) ? self::$dummyCache[$key] : null;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return bool
     */
    public function set($key, $value)
    {
        self::$dummyCache[$key] = $value;

        return true;
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function delete($key)
    {
        if (! array_key_exists($key, self::$dummyCache)) {
            return true;
        }

        unset(self::$dummyCache[$key]);

        return true;
    }

    /**
     * @return bool
     */
    public function clear()
    {
        self::$dummyCache = [];

        return true;
    }
}
