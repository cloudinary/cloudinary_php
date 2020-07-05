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
use Cloudinary\Configuration\Provisioning\ProvisioningAccountConfig;
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
        return Utils::tryParseUrl($cloudinaryUrl, [self::CLOUDINARY_URL_SCHEME]) ? true : false;
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

        $uri = Utils::tryParseUrl($cloudinaryUrl, [self::CLOUDINARY_URL_SCHEME]);

        if (! $uri) {
            throw new UnexpectedValueException(
            /** @lang text */
                'Invalid CLOUDINARY_URL, "cloudinary://[<key>:<secret>@]<cloud>" expected'
            );
        }

        $qParams = Utils::tryParseValues(StringUtils::parseQueryString($uri->getQuery()));

        $account = ['cloud_name' => $uri->getHost()];

        $userPass = explode(':', $uri->getUserInfo(), 2);

        ArrayUtils::addNonEmpty($account, 'api_key', ArrayUtils::get($userPass, 0));
        ArrayUtils::addNonEmpty($account, 'api_secret', ArrayUtils::get($userPass, 1));

        $config = array_merge($qParams, ['account' => $account]);

        $isPrivateCdn = ! empty($uri->getPath()) && $uri->getPath() !== '/';
        if ($isPrivateCdn) {
            $config = array_merge(
                $config,
                [
                    'url' => [
                        'secure_distribution' => substr($uri->getPath(), 1),
                        'private_cdn'         => $isPrivateCdn,
                    ],
                ]
            );
        }

        return $config;
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

        if (! empty($config['account'])) {
            $res .= "{$config['account']['api_key']}:{$config['account']['api_secret']}@";
        }

        $res .= ArrayUtils::get($config, ['account', 'cloud_name']);

        $res = ArrayUtils::implodeFiltered('/', [$res, ArrayUtils::get($config, ['url', 'secure_distribution'])]);

        return $res;
    }
}
