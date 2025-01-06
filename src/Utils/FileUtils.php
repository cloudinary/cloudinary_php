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

use Cloudinary\Api\Exception\GeneralError;
use GuzzleHttp\Psr7;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Class Utils
 *
 * @internal
 */
class FileUtils
{
    /**
     * @var int Maximum number of characters allowed for the file extension.
     */
    protected const MAX_FILE_EXTENSION_LEN = 5;

    protected const SUPPORTED_FETCH_PROTOCOLS = ['ftp', 'http', 'https', 's3', 'gs'];
    protected const BASE64_DATA_REGEX         = '/^data:([\w-]+\/[\w\-\+.]+)?(;[\w-]+=[\w-]+)*;base64,([a-zA-Z0-9\/+\n=]+)$/';

    /**
     * Helper function that removes current dir(.) if no dirname found.
     *
     *
     */
    public static function dirName($path): string|array|null
    {
        return ! empty($path) && pathinfo($path, PATHINFO_DIRNAME) !== '.' ? pathinfo($path, PATHINFO_DIRNAME) : null;
    }

    /**
     * Returns filename and extension for the given path.
     *
     * In case the path does not have an extension, null value for the extension is returned.
     *
     * @param string $fullPath The path to split.
     *
     * @return array containing filename and extension.
     */
    public static function splitPathFilenameExtension(string $fullPath): array
    {
        if (empty($fullPath)) {
            return ['', '', ''];
        }

        $path = self::dirName($fullPath);

        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);

        if (strlen($extension) <= self::MAX_FILE_EXTENSION_LEN) {
            $filename = pathinfo($fullPath, PATHINFO_FILENAME);
        } else {
            $filename  = pathinfo($fullPath, PATHINFO_BASENAME);
            $extension = null;
        }

        return [$path, $filename, $extension];
    }

    /**
     * Removes file extension from the file path (can be full path or just filename)
     *
     *
     */
    public static function removeFileExtension($path): string
    {
        return implode('/', ArrayUtils::safeFilter([self::dirName($path), pathinfo($path, PATHINFO_FILENAME)]));
    }

    /**
     * Determines whether the provided string is a valid base64 data string.
     *
     * @param mixed $data The candidate to check.
     *
     * @return false|int
     */
    public static function isBase64Data(mixed $data): bool|int
    {
        return is_string($data) && preg_match(self::BASE64_DATA_REGEX, $data);
    }

    /**
     * Tries to parse the provided URL.
     *
     * @param mixed $url The URL candidate.
     *
     * @return bool|UriInterface URL or false if not a valid URL.
     */
    public static function tryGetFetchUrl(mixed $url): UriInterface|bool
    {
        return Utils::tryParseUrl($url, self::SUPPORTED_FETCH_PROTOCOLS);
    }

    /**
     * Everything that is not a supported URL or Base64 data string is considered as a potential local file.
     *
     * @param mixed $path Path candidate to check.
     *
     */
    public static function isLocalFilePath(mixed $path): bool
    {
        return is_string($path) && ! (self::tryGetFetchUrl($path) || self::isBase64Data($path));
    }

    /**
     * Safe version of fopen that throws an exception when file can't be opened (non-existing, permissions, etc).
     *
     * @param string $filename       The file to open.
     * @param string $mode           Access mode.
     * @param bool   $useIncludePath Set to true if you want to search for the file in the include_path.
     *
     * @return resource
     *
     * @throws GeneralError
     *
     * @see fopen
     */
    public static function safeFileOpen(string $filename, string $mode, bool $useIncludePath = false)
    {
        if (empty($filename)) {
            throw new GeneralError('Path cannot be empty');
        }

        $fp = @fopen($filename, $mode, $useIncludePath);

        if (! $fp) {
            $err = error_get_last();
            throw new GeneralError($err['message']);
        }

        return $fp;
    }

    /**
     * Internal helper method for preparing file for the upload.
     *
     * @param mixed $file The file.
     *
     *
     * @throws GeneralError
     * @internal
     */
    public static function handleFile(mixed $file): UriInterface|StreamInterface|bool
    {
        $url = self::tryGetFetchUrl($file);
        if ($url !== false) {
            return $url;
        }

        if (self::isBase64Data($file)) {
            $file = Psr7\Utils::streamFor($file);
        } elseif (is_string($file)) {
            $file = self::safeFileOpen($file, 'rb');
        }

        return Psr7\Utils::streamFor($file);
    }
}
