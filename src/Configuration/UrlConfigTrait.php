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
 * Trait UrlConfigTrait
 *
 * @api
 */
trait UrlConfigTrait
{
    /**
     * Whether to automatically build URLs with multiple CDN sub-domains.
     *
     * @param bool $cdnSubdomain
     *
     * @return $this
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#multiple_sub_domains
     */
    public function cdnSubdomain($cdnSubdomain = true)
    {
        return $this->setUrlConfig(UrlConfig::CDN_SUBDOMAIN, $cdnSubdomain);
    }

    /**
     * Whether to use secure CDN sub-domain.
     *
     * @param bool $secureCdnSubdomain
     *
     * @return $this
     */
    public function secureCdnSubdomain($secureCdnSubdomain = true)
    {
        return $this->setUrlConfig(UrlConfig::SECURE_CDN_SUBDOMAIN, $secureCdnSubdomain);
    }

    /**
     * The custom domain name to use for building HTTP URLs.
     *
     * Relevant only for Advanced plan users that have a private CDN distribution and a custom CNAME.
     *
     * @param string $cname
     *
     * @return $this
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#private_cdns_and_cnames
     *
     */
    public function cname($cname)
    {
        return $this->setUrlConfig(UrlConfig::CNAME, $cname);
    }

    /**
     * Force HTTPS URLs for resources even if they are embedded in non-secure HTTP pages.
     *
     * @param bool $secure
     *
     * @return $this
     */
    public function secure($secure = true)
    {
        return $this->setUrlConfig(UrlConfig::SECURE, $secure);
    }

    /**
     * The domain name of the CDN distribution to use for building HTTPS URLs.
     * Relevant only for Advanced plan users that have a private CDN distribution.
     *
     * @param string $secureCname The CNAME for secure (https) URLs.
     *
     * @return $this
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#private_cdns_and_cnames
     *
     */
    public function secureCname($secureCname)
    {
        return $this->setUrlConfig(UrlConfig::SECURE_CNAME, $secureCname);
    }

    /**
     * Set this parameter to true if you are an Advanced plan user with a private CDN distribution.
     *
     * @param bool $privateCdn
     *
     * @return $this
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#private_cdns_and_cnames
     *
     */
    public function privateCdn($privateCdn = true)
    {
        return $this->setUrlConfig(UrlConfig::PRIVATE_CDN, $privateCdn);
    }

    /**
     * Set to true to create a signed Cloudinary URL.
     *
     * @param bool $signUrl
     *
     * @return $this
     */
    public function signUrl($signUrl = true)
    {
        return $this->setUrlConfig(UrlConfig::SIGN_URL, $signUrl);
    }

    /**
     * Setting both this and signUrl to true will sign the URL using the first 32 characters of a SHA-256 hash.
     *
     * @param bool $longUrlSignature
     *
     * @return $this
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#generating_delivery_url_signatures
     */
    public function longUrlSignature($longUrlSignature = true)
    {
        return $this->setUrlConfig(UrlConfig::LONG_URL_SIGNATURE, $longUrlSignature);
    }

    /**
     * Set to true to use shorten asset type.
     *
     * @param bool $shorten
     *
     * @return $this
     */
    public function shorten($shorten = true)
    {
        return $this->setUrlConfig(UrlConfig::SHORTEN, $shorten);
    }

    /**
     * Set to true to omit type and resource_type in the URL.
     *
     * @param bool $useRootPath
     *
     * @return $this
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#root_path_urls
     *
     */
    public function useRootPath($useRootPath = true)
    {
        return $this->setUrlConfig(UrlConfig::USE_ROOT_PATH, $useRootPath);
    }

    /**
     * Set to false to omit default version string for assets in folders in the delivery URL.
     *
     * @param bool $forceVersion
     *
     * @return $this
     */
    public function forceVersion($forceVersion = true)
    {
        return $this->setUrlConfig(UrlConfig::FORCE_VERSION, $forceVersion);
    }

    /**
     * Set to false to omit analytics.
     *
     * @param bool $analytics Whether to include analytics.
     *
     * @return $this
     */
    public function analytics($analytics = true)
    {
        return $this->setUrlConfig(UrlConfig::ANALYTICS, $analytics);
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
    abstract public function setUrlConfig($configKey, $configValue);
}
