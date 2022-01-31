<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Configuration;

use Cloudinary\ArrayUtils;
use Cloudinary\StringUtils;
use Cloudinary\Utils;
use InvalidArgumentException;
use UnexpectedValueException;

/**
 * Class ConfigUtils
 *
 * @internal
 */
class ConfigUtils
{
    const CLOUDINARY_URL_SCHEME = 'cloudinary';

    /**
     * Checks whether the supplied string is a valid cloudinary url
     *
     * @param string $cloudinaryUrl Cloudinary url candidate
     *
     * @return bool
     */
    public static function isCloudinaryUrl($cloudinaryUrl)
    {
        return Utils::tryParseUrl(self::normalizeCloudinaryUrl($cloudinaryUrl), [self::CLOUDINARY_URL_SCHEME]) ? true
            : false;
    }

    /**
     * Parses cloudinary url and fills in array that can be consumed by Configuration.
     *
     * @param string $cloudinaryUrl The Cloudinary Url
     *
     * @return array
     */
    public static function parseCloudinaryUrl($cloudinaryUrl)
    {
        if (empty($cloudinaryUrl)) {
            throw new InvalidArgumentException(
                'CLOUDINARY_URL cannot be empty'
            );
        }

        $uri = Utils::tryParseUrl(self::normalizeCloudinaryUrl($cloudinaryUrl), [self::CLOUDINARY_URL_SCHEME]);

        if (! $uri) {
            throw new UnexpectedValueException(
            /** @lang text */
                'Invalid CLOUDINARY_URL, "cloudinary://[<key>:<secret>@]<cloud>" expected'
            );
        }

        $qParams = Utils::tryParseValues(StringUtils::parseQueryString($uri->getQuery()));

        $cloud = [CloudConfig::CLOUD_NAME => $uri->getHost()];

        $userPass = explode(':', $uri->getUserInfo(), 2);

        ArrayUtils::addNonEmpty($cloud, CloudConfig::API_KEY, ArrayUtils::get($userPass, 0));
        ArrayUtils::addNonEmpty($cloud, CloudConfig::API_SECRET, ArrayUtils::get($userPass, 1));

        $config = array_merge_recursive($qParams, [CloudConfig::CONFIG_NAME => $cloud]);

        $isPrivateCdn = ! empty($uri->getPath()) && $uri->getPath() !== '/';
        if ($isPrivateCdn) {
            $config = array_merge(
                $config,
                [
                    UrlConfig::CONFIG_NAME => [
                        UrlConfig::SECURE_CNAME => substr($uri->getPath(), 1),
                        UrlConfig::PRIVATE_CDN  => $isPrivateCdn,
                    ],
                ]
            );
        }

        return $config;
    }

    /**
     * Tries to normalize the supplied cloudinary url string.
     *
     * @param string $cloudinaryUrl Cloudinary url candidate.
     *
     * @return string
     */
    public static function normalizeCloudinaryUrl($cloudinaryUrl)
    {
        if (! is_string($cloudinaryUrl)) {
            return $cloudinaryUrl;
        }

        return StringUtils::truncatePrefix($cloudinaryUrl, Configuration::CLOUDINARY_URL_ENV_VAR . '=');
    }

    /**
     * Builds the main part of the Cloudinary url (not including query parameters)
     *
     * @param array $config Configuration array
     *
     * @return string Resulting Cloudinary Url
     */
    public static function buildCloudinaryUrl($config)
    {
        $res = self::CLOUDINARY_URL_SCHEME . '://';

        if (! empty($config[CloudConfig::CONFIG_NAME])) {
            $res .= "{$config[CloudConfig::CONFIG_NAME][CloudConfig::API_KEY]}:" .
                    "{$config[CloudConfig::CONFIG_NAME][CloudConfig::API_SECRET]}@";
        }

        $res .= ArrayUtils::get($config, [CloudConfig::CONFIG_NAME, CloudConfig::CLOUD_NAME]);

        $res = ArrayUtils::implodeFiltered(
            '/',
            [$res, ArrayUtils::get($config, [UrlConfig::CONFIG_NAME, UrlConfig::SECURE_CNAME])]
        );

        return $res;
    }
}
