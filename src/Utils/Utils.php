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

use DateTime;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;

/**
 * Class Utils
 *
 * @internal
 */
class Utils
{
    /**
     * Converts a float value to the string representation.
     *
     * @param mixed $value
     *
     * @return mixed|string
     */
    public static function floatToString($value)
    {
        if (!is_float($value)) {
            return $value;
        }

        $locale = localeconv();
        $string = (string)$value;
        $string = str_replace($locale['decimal_point'], '.', $string);

        return $string;
    }

    /**
     * Helper method for converting boolean to string representation.
     *
     * @param mixed $value Candidate to convert. If not boolean, returned as is
     *
     * @return string
     */
    public static function boolToString($value)
    {
        if (!is_bool($value)) {
            return $value;
        }

        return $value ? 'true' : 'false';
    }

    /**
     * Helper method for converting boolean to int representation as string.
     *
     * @param mixed $value Candidate to convert. If not boolean, returned as is
     *
     * @return string
     */
    public static function boolToIntString($value)
    {
        if (!is_bool($value)) {
            return $value;
        }

        return $value ? '1' : '0';
    }

    /**
     * Helper method for normalizing values in string representation.
     *
     * @param mixed $value
     *
     * @return string
     */
    public static function normalizeToString($value)
    {
        return self::floatToString(self::boolToString($value));
    }

    /**
     * Converts bytes to kilobytes.
     *
     * @param int $bytes The input value to convert.
     *
     * @return int
     */
    public static function bytesToKB($bytes)
    {
        return (int)ceil($bytes / 1024);
    }

    /**
     * Used for formatting number to string with sign (+/-).
     *
     * @param int $number The number to format
     *
     * @return string
     */
    public static function formatSigned($number)
    {
        return $number > 0 ? "+$number" : (string)$number;
    }

    /**
     * Formats DateTime in ISO8601 format.
     *
     * @param DateTime|mixed $date The date to format.
     *
     * @return string The formatted date.
     */
    public static function formatDate($date)
    {
        if ($date instanceof DateTime) {
            $date = $date->format(DateTime::ATOM);
        }

        return $date;
    }

    /**
     * Creates a signature for content using specified secret.
     *
     * @param         $content
     * @param         $secret
     * @param bool    $raw
     *
     * @return string
     */
    public static function sign($content, $secret, $raw = null)
    {
        return sha1($content . $secret, $raw);
    }

    /**
     * Generates a random public ID string.
     *
     * @param string $prefix Provide unique prefix to avoid collisions, see {@see uniqid} for more details.
     *
     * @return bool|string
     */
    public static function randomPublicId($prefix = '')
    {
        return substr(sha1(uniqid($prefix . mt_rand(), true)), 0, 16);
    }

    /**
     * Parses URL if applicable, otherwise returns false.
     *
     * @param string|UriInterface $url            The URL to parse.
     * @param array|null          $allowedSchemes Optional array of the allowed schemes.
     *
     * @return false|UriInterface
     */
    public static function tryParseUrl($url, array $allowedSchemes = null)
    {
        if (!$url instanceof UriInterface) {
            if (!is_string($url)) {
                return false;
            }

            $urlParts = parse_url($url);
            if ($urlParts === false || count($urlParts) <= 1) {
                return false;
            }
            $url = Uri::fromParts($urlParts);
        }

        if ($allowedSchemes !== null && !in_array($url->getScheme(), $allowedSchemes, false)) {
            return false;
        }

        return $url;
    }

    /**
     * Returns current UNIX time in seconds.
     *
     * @return int
     */
    public static function unixTimeNow()
    {
        return time();
    }

    /**
     * Recursively casts all params from array to suggested type
     *
     * @param array $params The array of params
     *
     * @return array
     */
    public static function tryParseValues(array $params)
    {
        return array_map(
            static function ($value) {
                if (is_array($value)) {
                    return static::tryParseValues($value);
                }

                if (is_string($value)) {
                    if ($value === '[]') {
                        return [];
                    }

                    return static::tryParseBoolean($value);
                }

                return $value;
            },
            $params
        );
    }

    /**
     * Parses boolean from string
     *
     * @param $value
     *
     * @return bool
     */
    public static function tryParseBoolean($value)
    {
        if (!is_string($value)) {
            return $value;
        }

        if (ArrayUtils::inArrayI($value, ['true', 'false'])) {
            return stripos($value, 'true') === 0;
        }

        return $value;
    }
}
