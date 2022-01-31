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
    const QUERY_KEY    = '_a';
    const ALGO_VERSION = 'A'; // The version of the algorithm
    const SDK_CODE     = 'A'; // Cloudinary PHP SDK

    protected static $sdkVersion  = Cloudinary::VERSION;
    protected static $techVersion = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;

    const CHARS           = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
    const BINARY_PAD_SIZE = 6;

    protected static $charCodes = [];
    protected static $signature;

    /**
     * Gets the SDK signature by encoding the SDK version and tech version.
     *
     * @return string
     */
    public static function sdkAnalyticsSignature()
    {
        if (empty(static::$signature)) {
            // Lazily create $signature
            try {
                static::$signature = static::ALGO_VERSION . static::SDK_CODE .
                                     static::encodeVersion(static::$sdkVersion) .
                                     static::encodeVersion(static::$techVersion);
            } catch (OutOfRangeException $e) {
                static::$signature = 'E';
            }
        }

        return static::$signature;
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
    protected static function encodeVersion($version)
    {
        $parts = explode('.', $version);

        $paddedParts = array_map(
            static function ($part) {
                return str_pad((int)$part, 2, "0", STR_PAD_LEFT); // this also zeros non-numeric values
            },
            $parts
        );

        $number       = (int)implode(array_reverse($paddedParts));
        $paddedBinary = self::intToPaddedBin($number, count($parts) * self::BINARY_PAD_SIZE);

        if (strlen($paddedBinary) % self::BINARY_PAD_SIZE !== 0) {
            throw new OutOfRangeException('Version must be smaller than 43.21.26');
        }

        $encodedChars = array_map(
            static function ($part) {
                return self::getKey($part);
            },
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
    protected static function getKey($binaryValue)
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
     * @return string
     */
    protected static function intToPaddedBin($integer, $padNum)
    {
        return str_pad(decbin($integer), $padNum, "0", STR_PAD_LEFT);
    }
}
