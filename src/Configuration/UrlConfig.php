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

/**
 * Defines the global configuration applied when generating Cloudinary URLs.
 *
 * @property bool  $secure                        Force HTTPS URLs for resources even if they are embedded in
 *                                                non-secure HTTP pages.
 * @property bool  $forceVersion                  By default, set to self::DEFAULT_FORCE_VERSION.
 * @property mixed $responsiveWidthTransformation The transformation to use with responsive width.
 *
 * @api
 */
class UrlConfig extends BaseConfigSection
{
    use UrlConfigTrait;

    protected static array $aliases = ['secure_distribution' => self::SECURE_CNAME];

    /**
     * @internal
     */
    public const CONFIG_NAME = 'url';

    /**
     * @internal
     */
    public const DEFAULT_DOMAIN = 'cloudinary.com';

    /**
     * @internal
     */
    public const DEFAULT_SUB_DOMAIN = 'res';

    /**
     * @internal
     */
    public const DEFAULT_SHARED_HOST = self::DEFAULT_SUB_DOMAIN . '.' . self::DEFAULT_DOMAIN;

    public const PROTOCOL_HTTP  = 'http';
    public const PROTOCOL_HTTPS = 'https';

    /**
     * Default value for secure (distribution).
     */
    public const DEFAULT_SECURE = true;

    /**
     * Default value for forcing version.
     */
    public const DEFAULT_FORCE_VERSION = true;

    /**
     * Default value for analytics.
     */
    public const DEFAULT_ANALYTICS = true;

    /**
     * Default responsive width transformation.
     */
    public const DEFAULT_RESPONSIVE_WIDTH_TRANSFORMATION = 'c_limit,w_auto';

    // Supported parameters
    public const CDN_SUBDOMAIN        = 'cdn_subdomain';
    public const SECURE_CDN_SUBDOMAIN = 'secure_cdn_subdomain';
    public const CNAME                = 'cname';
    public const SECURE               = 'secure';
    public const SECURE_CNAME         = 'secure_cname';
    public const PRIVATE_CDN          = 'private_cdn';

    public const SIGN_URL           = 'sign_url';
    public const LONG_URL_SIGNATURE = 'long_url_signature';
    public const SHORTEN            = 'shorten';
    public const USE_ROOT_PATH      = 'use_root_path';
    public const FORCE_VERSION      = 'force_version';
    public const ANALYTICS          = 'analytics';

    public const RESPONSIVE_WIDTH                = 'responsive_width';
    public const RESPONSIVE_WIDTH_TRANSFORMATION = 'responsive_width_transformation';

    /**
     * Whether to automatically build URLs with multiple CDN sub-domains.
     *
     * @var ?bool
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#multiple_sub_domains
     */
    public ?bool $cdnSubdomain = null;

    /**
     * Secure CDN sub-domain.
     *
     * @var ?bool
     */
    public ?bool $secureCdnSubdomain = null;

    /**
     * The custom domain name to use for building HTTP URLs. Relevant only for Advanced plan users that have a private
     * CDN distribution and a custom CNAME
     *
     * @var ?string
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#private_cdns_and_cnames
     */
    public ?string $cname = null;

    /**
     * Force HTTPS URLs for resources even if they are embedded in non-secure HTTP pages.
     *
     * @var bool
     */
    protected bool $secure;

    /**
     * The domain name of the CDN distribution to use for building HTTPS URLs. Relevant only for Advanced plan users
     * that have a private CDN distribution.
     *
     * @var string|null
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#private_cdns_and_cnames
     */
    public ?string $secureCname = null;

    /**
     * Set this parameter to true if you are an Advanced plan user with a private CDN distribution.
     *
     * @var bool
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#private_cdns_and_cnames
     */
    public ?bool $privateCdn = null;

    /**
     * Set to true to create a Cloudinary URL signed with the first 8 characters of a SHA-1 hash.
     *
     * @var bool
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#generating_delivery_url_signatures
     */
    public ?bool $signUrl = null;

    /**
     * Setting both this and signUrl to true will sign the URL using the first 32 characters of a SHA-256 hash.
     *
     * @var bool
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#generating_delivery_url_signatures
     */
    public ?bool $longUrlSignature = null;

    /**
     * Set to true to use shorten asset type.
     *
     * @var bool
     */
    public ?bool $shorten = null;

    /**
     * Set to true to omit type and resource_type in the URL.
     *
     * @var bool
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#root_path_urls
     */
    public ?bool $useRootPath = null;

    /**
     * Set to false in order to omit default version string for assets in folders in the delivery URL.
     *
     * @var bool
     */
    protected ?bool $forceVersion = null;

    /**
     * Set to false in order to omit analytics data.
     *
     * @var bool
     */
    protected bool $analytics;

    /**
     * Whether to use responsive width.
     *
     * @var bool $responsiveWidth
     */
    public ?bool $responsiveWidth = null;

    /**
     * The transformation to use with responsive width.
     *
     * @var mixed $responsiveWidthTransformation
     */
    protected mixed $responsiveWidthTransformation = null;

    /**
     * Serialises configuration section to a string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString([self::SECURE_CNAME, self::PRIVATE_CDN]);
    }


    /**
     * Sets the Url configuration key with the specified value.
     *
     * @param string $configKey   The configuration key.
     * @param mixed  $configValue THe configuration value.
     *
     * @return $this
     *
     * @internal
     */
    public function setUrlConfig(string $configKey, mixed $configValue): static
    {
        return $this->setConfig($configKey, $configValue);
    }
}
