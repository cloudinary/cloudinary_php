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

use InvalidArgumentException;
use JsonSerializable;

/**
 * Class JsonUtils
 *
 * @internal
 */
class JsonUtils
{
    /**
     * Determines whether the input is a valid JSON string.
     *
     * @param string $string The input string.
     *
     * @return bool
     */
    public static function isJsonString($string)
    {
        return (is_string($string)
                && is_array(json_decode($string, true)) //TODO: improve performance
                && (json_last_error() === JSON_ERROR_NONE));
    }

    /**
     * Wrapper around json_decode, throws exception on error.
     *
     * @param mixed $json    JSON to decode.
     * @param bool  $assoc   Whether to convert object to an array.
     * @param int   $depth   Maximum depth.
     * @param int   $options Additional options.
     *
     * @return mixed
     *
     * @throws InvalidArgumentException
     *
     * @see json_decode
     */
    public static function decode($json, $assoc = true, $depth = 512, $options = 0)
    {
        if (is_array($json)) { // Already a json array, skip decoding.
            return $json;
        }

        $result = json_decode($json, $assoc, $depth, $options);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('JsonException : ' . json_last_error_msg());
        }

        return $result;
    }

    /**
     * Wrapper around json_encode, throws exception on error.
     *
     * @param mixed $value   The value to encode.
     * @param int   $options Additional options.
     *
     * @param int   $depth
     *
     * @return false|string
     *
     * @throws InvalidArgumentException
     *
     * @see json_encode
     */
    public static function encode($value, $options = 0, $depth = 512)
    {
        $result = json_encode($value, $options, $depth);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('JsonException : ' . json_last_error_msg());
        }

        return $result;
    }

    /**
     * Wrapper for \JsonSerializable::jsonSerialize, can be called on null or non-objects.
     *
     * @param JsonSerializable|mixed $jsonSerializable The serializable to serialize.
     *
     * @return mixed
     *
     * @see \JsonSerializable::jsonSerialize
     */
    public static function jsonSerialize($jsonSerializable)
    {
        if ($jsonSerializable === null) {
            return [];
        }

        if (is_array($jsonSerializable)) { // Already a json array, skip serialization
            return $jsonSerializable;
        }

        return $jsonSerializable->jsonSerialize();
    }
}
