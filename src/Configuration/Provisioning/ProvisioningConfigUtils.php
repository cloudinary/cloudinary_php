<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Configuration\Provisioning;

use Cloudinary\ArrayUtils;
use Cloudinary\Utils;
use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use UnexpectedValueException;

/**
 * Class ProvisioningConfigUtils
 *
 * @internal
 */
class ProvisioningConfigUtils
{
    public const ACCOUNT_URL_SCHEME = 'account';

    /**
     * Checks whether the supplied string is a valid account url
     *
     * @param mixed $accountUrl Account url candidate
     *
     */
    public static function isAccountUrl(mixed $accountUrl): bool
    {
        if (! $accountUrl instanceof UriInterface && ! is_string($accountUrl)) {
            return false;
        }

        return (bool)Utils::tryParseUrl($accountUrl, [self::ACCOUNT_URL_SCHEME]);
    }

    /**
     * Parses cloudinary account url.
     *
     * @param string $accountUrl The Cloudinary Account Url
     *
     */
    public static function parseAccountUrl(string $accountUrl): array
    {
        if (empty($accountUrl)) {
            throw new InvalidArgumentException(
                'CLOUDINARY_ACCOUNT_URL cannot be empty'
            );
        }

        $uri = Utils::tryParseUrl($accountUrl, [self::ACCOUNT_URL_SCHEME]);

        if (! $uri) {
            throw new UnexpectedValueException(
            /** @lang text */
                'Invalid CLOUDINARY_ACCOUNT_URL, "account://[<key>:<secret>@]<account>" expected'
            );
        }

        $config = [ProvisioningAccountConfig::ACCOUNT_ID => $uri->getHost()];

        $userPass = explode(':', $uri->getUserInfo(), 2);

        ArrayUtils::addNonEmpty(
            $config,
            ProvisioningAccountConfig::PROVISIONING_API_KEY,
            ArrayUtils::get($userPass, 0)
        );
        ArrayUtils::addNonEmpty(
            $config,
            ProvisioningAccountConfig::PROVISIONING_API_SECRET,
            ArrayUtils::get($userPass, 1)
        );

        return [ProvisioningAccountConfig::CONFIG_NAME => $config];
    }
}
