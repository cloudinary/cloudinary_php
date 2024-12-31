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
use Cloudinary\Cloudinary;
use OutOfRangeException;

/**
 * Class Analytics
 *
 * @internal
 */
class Analytics
{
    public const QUERY_KEY    = '_a';
    protected const ALGO_VERSION = 'B'; // The version of the algorithm
    protected const SDK_CODE     = 'A'; // Cloudinary PHP SDK

    protected static string $product = 'A'; // Official SDK. Set to 'B' for integrations.
    protected static string $sdkCode = self::SDK_CODE;
    protected static string $sdkVersion = Cloudinary::VERSION;
    protected static string $techVersion = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;

    protected const CHARS           = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
    protected const BINARY_PAD_SIZE = 6;

    protected static array $charCodes = [];
    protected static ?string $signature;

    /**
     * Gets the SDK signature by encoding the SDK version and tech version.
     *
     */
    public static function sdkAnalyticsSignature(): string
    {
        if (empty(static::$signature)) {
            // Lazily create $signature
            try {
                static::$signature = static::ALGO_VERSION . static::$product . static::$sdkCode .
                                     static::encodeVersion(static::$sdkVersion) .
                                     static::encodeVersion(static::$techVersion);
            } catch (OutOfRangeException $e) {
                static::$signature = 'E';
            }
        }

        return static::$signature;
    }

    /**
     * Sets the product code.
     *
     * Used for integrations.
     *
     * @param string $product The product code to set. 'A' is for the official SDK. 'B' for integrations.
     *
     *
     * @internal
     */
    public static function product(string $product): void
    {
        static::$product = $product;
    }

    /**
     * Sets the SDK code.
     *
     * Used for integrations.
     *
     * @param string $sdkCode The SDK code to set.
     *
     *
     * @internal
     */
    public static function sdkCode(string $sdkCode): void
    {
        static::$sdkCode = $sdkCode;
    }

    /**
     * Sets the SDK version.
     *
     * Used for integrations.
     *
     * @param string $sdkVersion The SDK version to set (MAJOR.MINOR.PATCH), for example: "1.0.0".
     *
     *
     * @internal
     */
    public static function sdkVersion(string $sdkVersion): void
    {
        static::$sdkVersion = $sdkVersion;
    }

    /**
     * Sets the tech version.
     *
     * Used for integrations.
     *
     * @param string $techVersion The tech version to set (MAJOR.MINOR), for example: "1.0".
     *
     *
     * @internal
     */
    public static function techVersion(string $techVersion): void
    {
        static::$techVersion = join('.', array_slice(explode('.', $techVersion), 0, 2));
    }

    /**
     * Encodes a semVer-like version string.
     *
     * Example:
     *  input:      '1.24.0'
     *  explode:    ['1','24','0']
     *  pad:        ['01','24','00']
     *  reverse:    ['00', '24', '01']
     *  implode:    '002401'
     *  int:        2401
     *  binary:     '100101100001'
     *  padded:     '000000100101100001'
     *  str_split:  ['000000', '100101', '100001']
     *  getKey:     ['A', 'l', 'h']
     *  implode:    'Alh'
     *
     * @param string $version Can be either x.y.z or x.y
     *
     * @return string A string built from 3 characters of the base64 table
     *
     * @throws OutOfRangeException when version is larger than 43.21.26
     */
    protected static function encodeVersion(string $version): string
    {
        $parts = explode('.', $version);

        $paddedParts = array_map(
            // this also zeros non-numeric values
            static fn($part) => str_pad((int)$part, 2, '0', STR_PAD_LEFT),
            $parts
        );

        $number       = (int)implode(array_reverse($paddedParts));
        $paddedBinary = self::intToPaddedBin($number, count($parts) * self::BINARY_PAD_SIZE);

        if (strlen($paddedBinary) % self::BINARY_PAD_SIZE !== 0) {
            throw new OutOfRangeException('Version must be smaller than 43.21.26');
        }

        $encodedChars = array_map(
            static fn($part) => self::getKey($part),
            str_split($paddedBinary, self::BINARY_PAD_SIZE)
        );


        return implode($encodedChars);
    }

    /**
     * Gets the key for binary value.
     *
     * @param string $binaryValue The value.
     *
     * @return array|mixed
     */
    protected static function getKey(string $binaryValue): mixed
    {
        if (empty(self::$charCodes)) {
            // let's lazily populate $charCodes
            for ($i = 0, $len = strlen(self::CHARS); $i < $len; $i++) {
                self::$charCodes[self::intToPaddedBin($i, self::BINARY_PAD_SIZE)] = self::CHARS[$i];
            }
        }

        return ArrayUtils::get(self::$charCodes, $binaryValue, '');
    }

    /**
     * Converts integer to left padded binary string.
     *
     * @param int $integer The input.
     * @param int $padNum  The num of padding chars.
     *
     */
    protected static function intToPaddedBin(int $integer, int $padNum): string
    {
        return str_pad(decbin($integer), $padNum, '0', STR_PAD_LEFT);
    }
}
