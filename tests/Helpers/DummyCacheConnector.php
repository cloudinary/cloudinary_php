<?php

namespace CloudinaryTest;

/**
 * Class DummyCacheConnector
 * @package CloudinaryTest
 */
class DummyCacheConnector
{
    private static $dummyCache = [];

    /**
     * @param $key
     *
     * @return mixed
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
        if (!array_key_exists($key, self::$dummyCache)) {
            return false;
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
