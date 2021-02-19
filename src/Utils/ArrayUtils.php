<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary;

/**
 * Class ArrayUtils
 *
 * @internal
 */
class ArrayUtils
{
    /**
     * Applies the callback to the elements of the given associative array
     *
     * @param callable $callback The callback function
     * @param array    $array    An array to run through the callback function.
     *
     * @return array Resulting array
     */
    public static function mapAssoc($callback, $array)
    {
        $r = [];
        foreach ($array as $key => $value) {
            $r[$key] = $callback($key, $value);
        }

        return $r;
    }

    /**
     * Joins associative array keys and values.
     *
     * @param array  $array An array to join
     * @param string $outer Outer delimiter (between pairs)
     * @param string $inner Inner delimiter (between key and value), if not provided $outer is used
     *
     * @return string Resulting string
     */
    public static function implodeAssoc($array, $outer = '', $inner = null)
    {
        if (! is_array($array)) {
            return $array === null ? null : (string)$array;
        }

        if ($inner === null) {
            $inner = $outer;
        }

        if (self::isAssoc($array)) { // implode key-value pairs using inner
            $array = self::mapAssoc(
                static function ($k, $v) use ($inner) {
                    if (! self::safeFilterFunc($v)) {
                        return null;
                    }

                    return self::implodeFiltered($inner, [$k, $v]);
                },
                $array
            );
        }

        return self::implodeFiltered($outer, self::safeFilter($array));
    }

    /**
     * Safe version of ArrayUtils::implodeAssoc.
     *
     * In addition escapes appearances of the delimiters inside strings.
     *
     * @param array  $array       An array to join
     * @param string $outer       Outer delimiter (between pairs)
     * @param string $inner       Inner delimiter (between key and value), if not provided $outer is used
     * @param bool   $innerIsSafe Whether to omit escaping of the inner delimiter
     *
     * @return string
     *
     * @see ArrayUtils::implodeAssoc
     */
    public static function safeImplodeAssoc($array, $outer = '', $inner = null, $innerIsSafe = false)
    {
        if (! is_array($array)) {
            return $array === null ? null : (string)$array;
        }

        if ($inner === null) {
            $inner = $outer;
        }

        if (self::isAssoc($array)) { // implode key-value pairs using inner
            $array = self::mapAssoc(
                static function ($k, $v) use ($inner, $innerIsSafe) {
                    if ($innerIsSafe) {
                        return self::implodeFiltered($inner, [$k, $v]);
                    }

                    return self::escapedImplode($inner, [$k, $v]);
                },
                $array
            );
        }

        return self::escapedImplode($outer, self::safeFilter($array));
    }

    /**
     * Shortcut helper
     *
     * Strange signature comes from the built-in implode function that has a similar signature.
     *
     * @param string $glue
     * @param array  $pieces
     * @param string $filterCallback
     * @param int    $flag
     *
     * @return string The resulting string
     *
     * @internal
     *
     * @see          implode
     */
    public static function implodeFiltered(
        $glue,
        array $pieces,
        $filterCallback = __NAMESPACE__ . '\ArrayUtils::safeFilterFunc',
        $flag = 0
    ) {
        return self::safeImplode($glue, self::safeFilter($pieces, $filterCallback, $flag));
    }

    /**
     * Safe version of implode.
     *
     * In addition fixes serialisation of float values.
     *
     * @param       $glue
     * @param array $pieces
     *
     * @return string
     */
    public static function safeImplode($glue, array $pieces)
    {
        array_walk(
            $pieces,
            static function (&$value) {
                $value = Utils::floatToString($value);
            }
        );

        return implode($glue, $pieces);
    }

    /**
     * Implodes array values with escaping the glue character.
     *
     * @param string $glue
     * @param array  $pieces
     *
     * @return string
     */
    public static function escapedImplode($glue, array $pieces)
    {
        return implode(
            $glue,
            array_map(
                static function ($value) use ($glue) {
                    return StringUtils::escapeUnsafeChars($value, $glue);
                },
                $pieces
            )
        );
    }

    /**
     * Uses "strlen()" instead of "empty()" to indicate whether value is considered empty.
     *
     * Used to keep falsy values, like 0, '0', etc.
     *
     * @param mixed|array $value The value to filter
     *
     * @return int
     *
     * @see strlen
     * @see empty
     */
    protected static function safeFilterFunc($value)
    {
        if (is_array($value)) {
            foreach ($value as $val) {
                if (self::safeFilterFunc($val)) {
                    return true;
                }
            }

            return false;
        }

        return strlen($value);
    }

    /**
     * Safe version of the "array_filter" function.
     *
     * Uses "strlen" filter function by default, which treats non-null values (e.g. 0, false, etc) as non-empty.
     *
     * @param array           $input
     * @param callback|string $callback
     * @param int             $flag
     *
     * @return array
     *
     * @see array_filter
     * @see strlen
     */
    public static function safeFilter(
        array $input,
        $callback = __NAMESPACE__ . '\ArrayUtils::safeFilterFunc',
        $flag = 0
    ) {
        return array_filter($input, $callback, $flag);
    }

    /**
     * Sorts associative array $array keys using order defined in $orderArray.
     *
     * In case some of the missing/extra keys, the keys are sorted using alphabetic order. Ordered keys come first.
     *
     * @param array $array      The associate array to order.
     * @param array $orderArray The desired order of the keys.
     *
     * @return array
     */
    public static function sortByArray($array, $orderArray = [])
    {
        if (! is_array($array)) {
            return $array;
        }

        ksort($array);

        return array_replace(array_intersect_key(array_flip($orderArray), $array), $array);
    }

    /**
     * Commonly used util for building Cloudinary URL
     *
     * @param array $urlParts
     *
     * @return string The resulting string
     *
     * @internal
     */
    public static function implodeUrl(array $urlParts)
    {
        return self::implodeFiltered('/', $urlParts);
    }

    /**
     * Commonly used util for building transformation URL
     *
     * @param array $qualifiers
     *
     * @return string The resulting string
     *
     * @internal
     */
    public static function implodeActionQualifiers(...$qualifiers)
    {
        $serializedQualifiers = array_map('strval', $qualifiers);

        sort($serializedQualifiers);

        return self::implodeFiltered(',', $serializedQualifiers);
    }

    /**
     * Commonly used util for building transformation URL
     *
     * @param array $qualifierValues
     *
     * @return string The resulting string
     *
     * @internal
     */
    public static function implodeQualifierValues(...$qualifierValues)
    {
        return self::implodeFiltered(':', $qualifierValues);
    }

    /**
     * Gets a key from an array if exists, otherwise returns default.
     *
     * @param array            $array   The data array.
     * @param string|int|array $key     The key. Can be a simple key(string|int), an index array that allows accessing
     *                                  nested values
     * @param mixed|null       $default The default value for the case when the key is not found.
     *
     * @return mixed
     */
    public static function get($array, $key, $default = null)
    {
        if (is_array($key)) {
            $currLevel = &$array;
            foreach ($key as $currKey) {
                if (! is_array($currLevel) || ! array_key_exists($currKey, $currLevel)) { //not found
                    return $default;
                }

                $currLevel = $currLevel[$currKey];
            }

            return $currLevel;
        }

        if (is_array($array) && array_key_exists($key, $array)) {
            return $array[$key];
        }

        return $default;
    }

    /**
     * Pops a key from an array if exists, otherwise returns default
     *
     * @param array            $array   Data array
     * @param string|int|array $key     key can be a simple key(string|int) or an array that allows accessing nested
     *                                  values
     * @param mixed|null       $default Default value for the case when key is not found
     *
     * @return mixed
     */
    public static function pop(&$array, $key, $default = null)
    {
        $val = self::get($array, $key, $default);
        unset($array[$key]);

        return $val;
    }

    /**
     * Returns a subset of associative array whitelisted by an array of keys
     *
     * @param array $array Source array (associative or not)
     * @param array $keys  Simple array of keys to keep
     *
     * @return array Resulting array
     */
    public static function whitelist($array, $keys)
    {
        // When providing non array(for example null), return it as is
        if (! is_array($array)) {
            return $array;
        }

        if (self::isAssoc($array)) {
            return array_intersect_key($array, array_flip($keys));
        }

        return array_intersect($array, $keys);
    }

    /**
     * Returns a subset of associative array with excluded keys specified by an array of keys
     *
     * @param array $array           Source array (associative or not)
     * @param array $blacklistedKeys Simple array of keys to be excluded
     *
     * @return array Resulting array
     */
    public static function blacklist($array, $blacklistedKeys)
    {
        // When providing non array (for example null), return it as is
        if (! is_array($array)) {
            return $array;
        }

        if (self::isAssoc($array)) {
            return array_diff_key($array, array_flip($blacklistedKeys));
        }

        return array_diff($array, $blacklistedKeys);
    }

    /**
     * Wraps a $value with an array, if not already an array.
     *
     * @param mixed $value The input value to wrap
     *
     * @return array
     */
    public static function build($value)
    {
        if ($value === null) {
            return [];
        }

        if (is_array($value) && ! self::isAssoc($value)) {
            return $value;
        }

        return [$value];
    }

    /**
     * Returns the first item value from the array in case it has only 1 element.
     *
     * It is the opposite of ArrayUtils::build function.
     *
     * @param mixed $array     Input array
     *
     * @param bool  $onlyAssoc Set to true to flatten only associative arrays
     *
     * @return null|string|array
     *
     * @see ArrayUtils::build
     */
    public static function flatten($array, $onlyAssoc = false)
    {
        if (empty($array) || ! is_array($array) || count($array) > 1) {
            return $array;
        }

        if ($onlyAssoc && ! self::isAssoc($array[0])) {
            return $array;
        }

        return $array[0];
    }

    /**
     * Determines whether an array is associative or not
     *
     * @param array $array Input array
     *
     * @return bool true if associative, otherwise false
     */
    public static function isAssoc($array)
    {
        if (! is_array($array)) {
            return false;
        }

        return $array !== array_values($array);
    }

    /**
     * Prepends associative element to the beginning of an array.
     *
     * @param array $arr The input array.
     * @param mixed $key The prepended key.
     * @param mixed $val The prepended value.
     *
     * @return array The resulting array.
     *
     * @internal
     */
    public static function prependAssoc(&$arr, $key, $val)
    {
        $arr       = array_reverse($arr, true);
        $arr[$key] = $val;
        $arr       = array_reverse($arr, true);

        return $arr;
    }

    /**
     * Appends element at the end of an array if not empty
     *
     * @param array $arr The input array.
     * @param mixed $val The appended value
     *
     * @return array The resulting array
     *
     * @internal
     *
     */
    public static function appendNonEmpty(&$arr, $val)
    {
        if (! empty($val)) {
            $arr [] = $val;
        }

        return $arr;
    }

    /**
     * Adds key-value pair to the associative array if the value is not empty
     *
     * @param array      $arr The input array.
     * @param string|int $key The key.
     * @param mixed      $val The appended value.
     *
     * @return array The resulting array
     *
     * @internal
     */
    public static function addNonEmpty(&$arr, $key, $val)
    {
        if (! empty($val) || is_int($val) || is_float($val) || is_bool($val)) {
            $arr[$key] = $val;
        }

        return $arr;
    }

    /**
     * Adds key-value pair to the associative array where value is taken from source array using the same key.
     *
     * @param array &$resultingArr The target array.
     * @param mixed  $key          The key to add
     * @param array  $sourceArray  The source array
     * @param mixed  $defaultValue Fallback value, in case the key does not exist or is empty
     *
     * @return array The resulting array
     *
     * @internal
     */
    public static function addNonEmptyFromOther(&$resultingArr, $key, $sourceArray, $defaultValue = null)
    {
        return self::addNonEmpty($resultingArr, $key, self::get($sourceArray, $key, $defaultValue));
    }

    /**
     * Merges non empty array values
     *
     * @param array[] $arrays The arrays to merge
     *
     * @return array The resulting array
     *
     * @internal
     *
     */
    public static function mergeNonEmpty(...$arrays)
    {
        $result = [];

        foreach ($arrays as $array) {
            $result = array_merge($result, self::safeFilter($array));
        }

        return $result;
    }

    /**
     * Inserts item to the array next to (before/after) specified key
     *
     * @param array $array            The input array
     * @param mixed $searchKey        The key to search for in the array
     * @param mixed $insertKey        New key to insert
     * @param mixed $insertValue      New value for the key
     * @param bool  $insertAfter      Whether to insert after the $searchKey
     * @param bool  $appendIfNotFound Whether append a new key-value pair at the end of the array in case $searchKey
     *                                was not found
     *
     * @return array The new array with the inserted key
     */
    public static function insertAt(
        $array,
        $searchKey,
        $insertKey,
        $insertValue,
        $insertAfter = true,
        $appendIfNotFound = true
    ) {
        $result = [];

        foreach ($array as $key => $value) {
            if ($key === $searchKey && ! $insertAfter) {
                $result[$insertKey] = $insertValue;
            }

            $result[$key] = $value;

            if ($key === $searchKey && $insertAfter) {
                $result[$insertKey] = $insertValue;
            }
        }

        if ($appendIfNotFound && count($array) === count($result)) {
            $result[$insertKey] = $insertValue;
        }

        return $result;
    }

    /**
     * Sets the $defaultValue in case the $key does not exist or empty.
     *
     * @param array $array        The input array.
     * @param mixed $key          The key.
     * @param mixed $defaultValue The default value.
     */
    public static function setDefaultValue(array &$array, $key, $defaultValue)
    {
        if (! isset($array[$key]) || ! self::safeFilterFunc($array[$key])) {
            $array[$key] = $defaultValue;
        }
    }

    /**
     * Inverts simple array (indexed by int) value and sets value to null, the rest leaves as is
     *
     * @param array $array The input array.
     *
     * @return array The resulting array.
     *
     * @internal
     */
    public static function convertToAssoc($array)
    {
        $result = [];
        foreach ($array as $k => $v) {
            if (is_int($k)) {
                $k = $v;
                $v = null;
            }
            $result[$k] = $v;
        }

        return $result;
    }

    /**
     * Helper function for making a recursive array copy while cloning objects on the way.
     *
     * @param array $array Source array
     *
     * @return array Recursive copy of the source array
     */
    public static function deepCopy($array)
    {
        if (! is_array($array)) {
            return $array;
        }

        $result = [];
        foreach ($array as $key => $val) {
            if (is_array($val)) {
                $result[$key] = self::deepCopy($val);
            } elseif (is_object($val)) {
                $result[$key] = clone $val;
            } else {
                $result[$key] = $val;
            }
        }

        return $result;
    }

    /**
     * Indicates whether all parameters are non-empty
     *
     * @param mixed ...$params
     *
     * @return bool
     */
    public static function all(...$params)
    {
        foreach ($params as $param) {
            if (empty($param)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Case insensitive version of in_array
     *
     * @param mixed $needle   The searched value.
     * @param array $haystack The array.
     *
     * @return bool
     */
    public static function inArrayI($needle, $haystack)
    {
        return in_array(strtolower($needle), array_map('strtolower', $haystack), false);
    }

    /**
     * Walks the array and converts all booleans to string representations of their int values
     *
     * true becomes '1', false becomes '0'
     *
     * @param array &$array The input array to convert.
     */
    public static function convertBoolToIntStrings(&$array)
    {
        array_walk(
            $array,
            static function (&$value) {
                $value = Utils::boolToIntString($value);
            }
        );
    }
}
