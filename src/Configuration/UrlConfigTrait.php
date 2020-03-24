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
 */
trait UrlConfigTrait
{
    /**
     * @param bool $cdnSubdomain Whether to automatically build URLs with multiple CDN sub-domains.
     *
     * @return $this
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#multiple_sub_domains
     */
    public function cdnSubdomain($cdnSubdomain = true)
    {
        return $this->setUrlConfig(UrlConfig::CDN_SUBDOMAIN, $cdnSubdomain);
    }

    /**
     * @param bool $secureCdnSubdomain Secure CDN sub-domain.
     *
     * @return $this
     */
    public function secureCdnSubdomain($secureCdnSubdomain = true)
    {
        return $this->setUrlConfig(UrlConfig::SECURE_CDN_SUBDOMAIN, $secureCdnSubdomain);
    }

    /**
     * @param string $cname The custom domain name to use for building HTTP URLs.
     *                      Relevant only for Advanced plan users that have a private CDN distribution and a custom
     *                      CNAME
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
     * @param bool $secure Force HTTPS URLs for resources even if they are embedded in non-secure HTTP pages.
     *
     * @return $this
     */
    public function secure($secure = true)
    {
        return $this->setUrlConfig(UrlConfig::SECURE, $secure);
    }

    /**
     * @param string $secureDistribution The domain name of the CDN distribution to use for building HTTPS URLs.
     *                                   Relevant only for Advanced plan users that have a private CDN distribution.
     *
     * @return $this
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#private_cdns_and_cnames
     *
     */
    public function secureDistribution($secureDistribution)
    {
        return $this->setUrlConfig(UrlConfig::SECURE_DISTRIBUTION, $secureDistribution);
    }

    /**
     * @param bool $privateCdn Set this parameter to true if you are an Advanced plan user with a private CDN
     *                         distribution
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
     * @param bool $signUrl Set to true to create a signed Cloudinary URL
     *
     * @return $this
     */
    public function signUrl($signUrl = true)
    {
        return $this->setUrlConfig(UrlConfig::SIGN_URL, $signUrl);
    }

    /**
     * @param bool $shorten Set to true to use shorten asset type
     *
     * @return $this
     */
    public function shorten($shorten = true)
    {
        return $this->setUrlConfig(UrlConfig::SHORTEN, $shorten);
    }

    /**
     * @param bool $useRootPath Set to true to omit type and resource_type in the URL
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
     * @param bool $forceVersion Set to false to omit default version string for assets in folders in the delivery URL
     *
     * @return $this
     */
    public function forceVersion($forceVersion = true)
    {
        return $this->setUrlConfig(UrlConfig::FORCE_VERSION, $forceVersion);
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
