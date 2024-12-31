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
use Cloudinary\Configuration\AuthTokenConfig;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Utils;
use InvalidArgumentException;
use UnexpectedValueException;

/**
 * Class AuthToken
 *
 * The token-based authentication feature allows you to limit the validity of the asset delivery URL to a specific time
 * frame. An authentication token is added as query parameters to the delivery URL, and is used to validate
 * authentication before delivering the asset.
 *
 * @see https://cloudinary.com/documentation/control_access_to_media#token_based_authentication
 *
 * @api
 */
class AuthToken
{
    public const     AUTH_TOKEN_NAME       = '__cld_token__';

    protected const UNSAFE = '/([ "#%&\'\/:;<=>?@\[\]^`{\|}~\\\])/';
    protected const  TOKEN_SEPARATOR       = '~';
    protected const  TOKEN_INNER_SEPARATOR = '=';
    protected const  TOKEN_ACL_SEPARATOR   = '!';

    /**
     * @var AuthTokenConfig $config The configuration of the authentication token.
     */
    public AuthTokenConfig $config;

    /**
     * AuthToken constructor.
     *
     * @param array|string|Configuration|null $configuration The Configuration source.
     */
    public function __construct(Configuration|array|string|null $configuration = null)
    {
        if ($configuration === null) {
            $configuration = Configuration::instance(); // get global instance
        }

        $this->configuration($configuration);
    }

    /**
     * AuthToken named constructor.
     *
     * @param array|string|Configuration|null $configuration The Configuration source.
     *
     */
    public static function fromJson(Configuration|array|string|null $configuration = null): AuthToken
    {
        return new self($configuration);
    }


    /**
     * Indicates whether according to the current configuration, AuthToken is enabled or not
     *
     */
    public function isEnabled(): bool
    {
        return ! empty($this->config->key);
    }

    /**
     * Sets the configuration.
     *
     * @param mixed $configuration The configuration to set.
     *
     */
    public function configuration(mixed $configuration): static
    {
        $tempConfiguration = new Configuration($configuration, false); // TODO: improve performance here

        $this->config = $tempConfiguration->authToken;

        return $this;
    }

    /**
     * Generates authorization digest.
     *
     * @param string $message The input to sign.
     *
     */
    private function digest(string $message): string
    {
        return hash_hmac('sha256', $message, pack('H*', $this->config->key));
    }

    /**
     *  Generates an authorization token.
     *  Options:
     *      number start_time - the start time of the token in seconds from epoch.
     *      string expiration - the expiration time of the token in seconds from epoch.
     *      string duration - the duration of the token (from start_time).
     *      string ip - the IP address of the client.
     *      string acl - the ACL for the token.
     *      string url - the URL to authentication in case of a URL token.
     *
     * @param string|null $path url path to sign. Ignored if acl is set.
     *
     * @return ?string The authorization token.
     *
     * @throws UnexpectedValueException if neither expiration nor duration nor one of acl or url were provided.
     */
    public function generate(?string $path = null): ?string
    {
        if (! $this->isEnabled()) {
            return null;
        }

        list($start, $expiration) = $this->handleLifetime();

        if (empty($path) && empty($this->config->acl)) {
            throw new UnexpectedValueException('AuthToken must contain either acl or url property');
        }

        $tokenParts = [];

        ArrayUtils::addNonEmpty($tokenParts, 'ip', $this->config->ip);
        ArrayUtils::addNonEmpty($tokenParts, 'st', $start);
        ArrayUtils::addNonEmpty($tokenParts, 'exp', $expiration);
        $acl = $this->config->acl;
        if (is_array($this->config->acl)) {
            $acl = implode(self::TOKEN_ACL_SEPARATOR, $this->config->acl);
        }
        ArrayUtils::addNonEmpty($tokenParts, 'acl', self::escapeToLower($acl));

        $toSign = $tokenParts;
        if (! empty($path) && empty($this->config->acl)) {
            ArrayUtils::addNonEmpty($toSign, 'url', self::escapeToLower($path));
        }

        $auth = $this->digest(ArrayUtils::implodeAssoc($toSign, self::TOKEN_SEPARATOR, self::TOKEN_INNER_SEPARATOR));
        ArrayUtils::addNonEmpty($tokenParts, 'hmac', $auth);

        return implode(
            self::TOKEN_INNER_SEPARATOR,
            [
                self::AUTH_TOKEN_NAME,
                ArrayUtils::implodeAssoc($tokenParts, self::TOKEN_SEPARATOR, self::TOKEN_INNER_SEPARATOR),
            ]
        );
    }

    /**
     * Validates and generates token validity.
     *
     * @return array including start time and expiration
     */
    private function handleLifetime(): array
    {
        $start      = $this->config->startTime;
        $expiration = $this->config->expiration;
        $duration   = $this->config->duration;

        if (! strcasecmp((string)$start, 'now')) {
            $start = Utils::unixTimeNow();
        } elseif (is_numeric($start)) {
            $start = (int)$start;
        }
        if (! isset($expiration)) {
            if (isset($duration)) {
                $expiration = ($start ?? Utils::unixTimeNow()) + $duration;
            } else {
                throw new InvalidArgumentException('Must provide \'expiration\' or \'duration\'.');
            }
        }

        return [$start, $expiration];
    }

    /**
     * Escapes a url using lowercase hex characters
     *
     * @param ?string $url The URL to escape
     *
     * @return string|string[]|null escaped URL
     */
    private static function escapeToLower(?string $url): array|string|null
    {
        if (empty($url)) {
            return $url;
        }

        return preg_replace_callback(
            self::UNSAFE,
            static fn($match) => '%' . bin2hex($match[1]),
            $url
        );
    }
}
