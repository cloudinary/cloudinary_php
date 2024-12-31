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
    public const ALGO_SHA1                      = 'sha1';
    public const ALGO_SHA256                    = 'sha256';
    public const SUPPORTED_SIGNATURE_ALGORITHMS = [self::ALGO_SHA1, self::ALGO_SHA256];
    public const SHORT_URL_SIGNATURE_LENGTH     = 8;
    public const LONG_URL_SIGNATURE_LENGTH      = 32;

    /**
     * Converts a float value to the string representation.
     *
     *
     * @return mixed|string
     */
    public static function floatToString(mixed $value): mixed
    {
        if (! is_float($value)) {
            return $value;
        }

        // Ensure that trailing decimal(.0) part is not cropped when float is provided.
        // e.g. float 1.0 should be returned as "1.0" and not "1" as it happens by default.
        if ($value - (int)$value === 0.0) {
            return sprintf('%.1f', $value);
        }

        $locale = localeconv();
        $string = (string)$value;

        return str_replace($locale['decimal_point'], '.', $string);
    }

    /**
     * Helper method for converting boolean to string representation.
     *
     * @param mixed $value Candidate to convert. If not boolean, returned as is
     *
     */
    public static function boolToString(mixed $value): string
    {
        if (! is_bool($value)) {
            return $value;
        }

        return $value ? 'true' : 'false';
    }

    /**
     * Helper method for converting boolean to int representation as string.
     *
     * @param mixed $value Candidate to convert. If not boolean, returned as is
     *
     */
    public static function boolToIntString(mixed $value): string
    {
        if (! is_bool($value)) {
            return $value;
        }

        return $value ? '1' : '0';
    }

    /**
     * Helper method for normalizing values in string representation.
     *
     *
     */
    public static function normalizeToString(mixed $value): string
    {
        return self::floatToString(self::boolToString($value));
    }

    /**
     * Converts bytes to kilobytes.
     *
     * @param int $bytes The input value to convert.
     *
     */
    public static function bytesToKB(int $bytes): int
    {
        return (int)ceil($bytes / 1024);
    }

    /**
     * Used for formatting number to string with sign (+/-).
     *
     * @param int $number The number to format
     *
     */
    public static function formatSigned(int $number): string
    {
        return $number > 0 ? "+$number" : (string)$number;
    }

    /**
     * Formats DateTime in ISO8601 format.
     *
     * @param DateTime|mixed $date The date to format.
     *
     * @return ?string The formatted date.
     */
    public static function formatDate(mixed $date): ?string
    {
        if ($date instanceof DateTime) {
            $date = $date->format(DateTime::ATOM);
        }

        return $date;
    }

    /**
     * Format a given signature hash into a string that will be used to sign a url
     *
     * @param string $signature The signature to format
     * @param int    $length    Number of characters to use from the start of the signature
     *
     */
    public static function formatSimpleSignature(string $signature, int $length): string
    {
        return 's--' . substr($signature, 0, $length) . '--';
    }

    /**
     * Formats a DateTime object to ISO8601 format (e.g., "2020-12-25").
     *
     *
     */
    public static function toISO8601DateOnly(DateTime $date): string
    {
        return $date->format('Y-m-d');
    }

    /**
     * Creates a signature for content using specified secret.
     *
     *
     */
    public static function sign(
        string $content,
        string $secret,
        bool $raw = false,
        string $algo = self::ALGO_SHA1
    ): string {
        return hash($algo, $content . $secret, $raw);
    }

    /**
     * Generates a random public ID string.
     *
     * @param string $prefix Provide unique prefix to avoid collisions, see uniqid for more details.
     *
     *
     * @see uniqid
     */
    public static function randomPublicId(string $prefix = ''): bool|string
    {
        return substr(sha1(uniqid($prefix . mt_rand(), true)), 0, 16);
    }

    /**
     * Parses URL if applicable, otherwise returns false.
     *
     * @param mixed      $url            The URL to parse.
     * @param array|null $allowedSchemes Optional array of the allowed schemes.
     */
    public static function tryParseUrl(
        mixed $url,
        ?array $allowedSchemes = null
    ): UriInterface|bool|string {
        if (! $url instanceof UriInterface) {
            if (! is_string($url)) {
                return false;
            }

            $urlParts = parse_url($url);
            if ($urlParts === false || count($urlParts) <= 1) {
                return false;
            }
            $url = Uri::fromParts($urlParts);
        }

        if ($allowedSchemes !== null && ! in_array($url->getScheme(), $allowedSchemes)) {
            return false;
        }

        return $url;
    }

    /**
     * Fixes array encoding.
     *
     * http_build_query encodes a simple array value:
     *   ['arr' => [v0', 'v1', ... ,'vn1]]
     * as:
     *   arr[0]=v0&arr[1]=v1&...&arr[n]=vn
     *
     * The issue with this encoding is that on the server written not in PHP,
     * this query is parsed as an associative array/hashmap/dictionary of form:
     *   {
     *     "arr": {
     *       "0" : "v0",
     *       "1" : "v1",
     *       ...
     *       "n" : "vn"
     *     }
     *   }
     *
     * To avoid this undesired behaviour, indices must be removed, so the query string would look like:
     * arr[]=v0&arr[]=v1&...&arr[]=vn
     *
     * @param array $params The query params to encode.
     *
     * @return string|string[]|null
     */
    public static function buildHttpQuery(array $params): array|string|null
    {
        return preg_replace('/%5B\d+%5D/', '%5B%5D', http_build_query($params));
    }


    /**
     * Returns current UNIX time in seconds.
     *
     */
    public static function unixTimeNow(): int
    {
        return time();
    }

    /**
     * Recursively casts all params from array to suggested type
     *
     * @param array $params The array of params
     *
     */
    public static function tryParseValues(array $params): array
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
     *
     */
    public static function tryParseBoolean($value): bool|string
    {
        if (! is_string($value)) {
            return $value;
        }

        if (ArrayUtils::inArrayI($value, ['true', 'false'])) {
            return stripos($value, 'true') === 0;
        }

        return $value;
    }

    /**
     * Convert an object to array.
     *
     *
     */
    public static function objectToArray($object): array
    {
        $properties = (array)$object;

        $snakeCaseProperties = [];
        foreach ($properties as $key => $value) {
            $key = str_replace(['*', "\0"], '', $key);
            $key = StringUtils::camelCaseToSnakeCase($key);

            if ($value === null) {
                continue;
            } elseif (is_object($value)) {
                $snakeCaseProperties[$key] = self::objectToArray($value);
            } elseif (is_array($value)) {
                $subArray = [];
                foreach ($value as $subArrayValue) {
                    $subArray[] = self::objectToArray($subArrayValue);
                }
                $snakeCaseProperties[$key] = $subArray;
            } else {
                $snakeCaseProperties[$key] = $value;
            }
        }

        return $snakeCaseProperties;
    }
}
