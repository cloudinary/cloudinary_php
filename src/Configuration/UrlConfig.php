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
 * @property bool   $secure                        Force HTTPS URLs for resources even if they are embedded in
 *           non-secure HTTP pages.
 * @property bool   $forceVersion                  By default set to self::DEFAULT_FORCE_VERSION.
 * @property string $responsiveWidthTransformation The transformation to use with responsive width.
 *
 * @api
 */
class UrlConfig extends BaseConfigSection
{
    use UrlConfigTrait;

    protected static $aliases = ['secure_distribution' => self::SECURE_CNAME];

    /**
     * @internal
     */
    const CONFIG_NAME = 'url';

    /**
     * @internal
     */
    const DEFAULT_DOMAIN = 'cloudinary.com';

    /**
     * @internal
     */
    const DEFAULT_SUB_DOMAIN = 'res';

    /**
     * @internal
     */
    const DEFAULT_SHARED_HOST = self::DEFAULT_SUB_DOMAIN . '.' . self::DEFAULT_DOMAIN;

    const PROTOCOL_HTTP  = 'http';
    const PROTOCOL_HTTPS = 'https';

    /**
     * Default value for secure (distribution).
     */
    const DEFAULT_SECURE = true;

    /**
     * Default value for forcing version.
     */
    const DEFAULT_FORCE_VERSION = true;

    /**
     * Default value for analytics.
     */
    const DEFAULT_ANALYTICS = true;

    /**
     * Default responsive width transformation.
     */
    const DEFAULT_RESPONSIVE_WIDTH_TRANSFORMATION = 'c_limit,w_auto';

    // Supported parameters
    const CDN_SUBDOMAIN        = 'cdn_subdomain';
    const SECURE_CDN_SUBDOMAIN = 'secure_cdn_subdomain';
    const CNAME                = 'cname';
    const SECURE               = 'secure';
    const SECURE_CNAME         = 'secure_cname';
    const PRIVATE_CDN          = 'private_cdn';

    const SIGN_URL           = 'sign_url';
    const LONG_URL_SIGNATURE = 'long_url_signature';
    const SHORTEN            = 'shorten';
    const USE_ROOT_PATH      = 'use_root_path';
    const FORCE_VERSION      = 'force_version';
    const ANALYTICS          = 'analytics';

    const RESPONSIVE_WIDTH                = 'responsive_width';
    const RESPONSIVE_WIDTH_TRANSFORMATION = 'responsive_width_transformation';

    /**
     * Whether to automatically build URLs with multiple CDN sub-domains.
     *
     * @var bool
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#multiple_sub_domains
     */
    public $cdnSubdomain;

    /**
     * Secure CDN sub-domain.
     *
     * @var bool
     */
    public $secureCdnSubdomain;

    /**
     * The custom domain name to use for building HTTP URLs. Relevant only for Advanced plan users that have a private
     * CDN distribution and a custom CNAME
     *
     * @var string
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#private_cdns_and_cnames
     */
    public $cname;

    /**
     * Force HTTPS URLs for resources even if they are embedded in non-secure HTTP pages.
     *
     * @var bool
     */
    protected $secure;

    /**
     * The domain name of the CDN distribution to use for building HTTPS URLs. Relevant only for Advanced plan users
     * that have a private CDN distribution.
     *
     * @var string
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#private_cdns_and_cnames
     */
    public $secureCname;

    /**
     * Set this parameter to true if you are an Advanced plan user with a private CDN distribution.
     *
     * @var bool
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#private_cdns_and_cnames
     */
    public $privateCdn;

    /**
     * Set to true to create a Cloudinary URL signed with the first 8 characters of a SHA-1 hash.
     *
     * @var bool
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#generating_delivery_url_signatures
     */
    public $signUrl;

    /**
     * Setting both this and signUrl to true will sign the URL using the first 32 characters of a SHA-256 hash.
     *
     * @var bool
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#generating_delivery_url_signatures
     */
    public $longUrlSignature;

    /**
     * Set to true to use shorten asset type.
     *
     * @var bool
     */
    public $shorten;

    /**
     * Set to true to omit type and resource_type in the URL.
     *
     * @var bool
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#root_path_urls
     */
    public $useRootPath;

    /**
     * Set to false to omit default version string for assets in folders in the delivery URL.
     *
     * @var bool
     */
    protected $forceVersion;

    /**
     * Set to false to omit analytics data.
     *
     * @var bool
     */
    protected $analytics;

    /**
     * Whether to use responsive width.
     *
     * @var bool $responsiveWidth
     */
    public $responsiveWidth;

    /**
     * The transformation to use with responsive width.
     *
     * @var string $responsiveWidthTransformation
     */
    protected $responsiveWidthTransformation;

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
    public function setUrlConfig($configKey, $configValue)
    {
        return $this->setConfig($configKey, $configValue);
    }
}
