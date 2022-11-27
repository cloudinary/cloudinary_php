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
use Cloudinary\Configuration\UrlConfig;
use Cloudinary\Exception\ConfigurationException;
use Cloudinary\StringUtils;
use Cloudinary\Utils;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\UriInterface;
use UnexpectedValueException;

/**
 * Trait AssetFinalizerTrait
 *
 * @property AssetDescriptor $asset
 * @property AuthToken       $authToken
 */
trait AssetFinalizerTrait
{
    /**
     *
     * Helper function for building hostname of form:
     *          subDomainPrefix-subDomain-subDomainSuffix.domain
     * For example:
     *          cloudname-res-3.cloudinary.com
     *
     * @param null   $subDomainPrefix
     * @param null   $subDomainSuffix
     * @param string $subDomain
     * @param string $domain
     *
     * @return string Resulting host name
     * @internal
     */
    private static function buildHostName(
        $subDomainPrefix = null,
        $subDomainSuffix = null,
        $subDomain = UrlConfig::DEFAULT_SUB_DOMAIN,
        $domain = UrlConfig::DEFAULT_DOMAIN
    ) {
        return implode(
            '.',
            array_filter(
                [
                    implode('-', array_filter([$subDomainPrefix, $subDomain, $subDomainSuffix])),
                    $domain,
                ]
            )
        );
    }

    /**
     * Computes the domain shard from the source.
     *
     * @param string $source The source.
     *
     * @return int between 1 and 5
     */
    private static function domainShard($source)
    {
        return (((crc32($source) % 5) + 5) % 5 + 1);
    }

    /**
     *  Builds the hostname for the asset distribution.
     *
     *   1) Customers in shared distribution (e.g. res.cloudinary.com)
     *      If cdn_domain is true uses res-[1-5].cloudinary.com for both http and https.
     *      Setting secure_cdn_subdomain to false disables this for https.
     *   2) Customers with private cdn
     *      If cdn_domain is true uses cloudname-res-[1-5].cloudinary.com for http
     *      If secure_cdn_domain is true uses cloudname-res-[1-5].cloudinary.com for https
     *      (please contact support if you require this)
     *   3) Customers with cname
     *      If cdn_domain is true uses a[1-5].cname for http.
     *      For https, uses the same naming scheme as 1 for shared distribution and as 2 for private distribution.
     *
     * @return mixed
     */
    protected function finalizeDistribution()
    {
        $useSharedHost = ! $this->urlConfig->privateCdn;

        if ($this->urlConfig->secure) {
            $protocol = UrlConfig::PROTOCOL_HTTPS;

            $hostName = $this->urlConfig->secureCname;
            if (empty($hostName)) {
                if ($this->urlConfig->privateCdn) {
                    $hostName = self::buildHostName($this->cloud->cloudName);
                } else {
                    $hostName      = UrlConfig::DEFAULT_SHARED_HOST;
                    $useSharedHost = true;
                }
            }

            $secureCdnSubDomain = $this->urlConfig->secureCdnSubdomain;
            if ($useSharedHost && $secureCdnSubDomain === null) {
                $secureCdnSubDomain = $this->urlConfig->cdnSubdomain;
            }

            if ($secureCdnSubDomain) {
                $hostName = str_replace(
                    UrlConfig::DEFAULT_SHARED_HOST,
                    self::buildHostName(null, self::domainShard($this->asset->publicId())),
                    $hostName
                );
            }
        } else {
            $protocol = UrlConfig::PROTOCOL_HTTP;

            if ($this->urlConfig->cname) {
                $hostName = self::buildHostName(
                    null,
                    null,
                    $this->urlConfig->cdnSubdomain ? 'a' . self::domainShard($this->asset->publicId()) : null,
                    $this->urlConfig->cname
                );
            } else {
                $hostName = self::buildHostName(
                    $this->urlConfig->privateCdn ? $this->cloud->cloudName : null,
                    $this->urlConfig->cdnSubdomain ? self::domainShard($this->asset->publicId()) : null
                );
            }
        }

        $distribution = "$protocol://$hostName";

        if ($useSharedHost) {
            $distribution .= "/{$this->cloud->cloudName}";
        }

        return $distribution;
    }

    /**
     * Finalizes the asset type.
     *
     * Used for SEO optimization.
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#seo_friendly_media_asset_urls
     *
     * @return mixed
     * @throws UnexpectedValueException
     */
    protected function finalizeAssetType()
    {
        if (! empty($this->asset->suffix)) {
            $suffixSupportedDeliveryTypes = static::getSuffixSupportedDeliveryTypes();
            if (! isset($suffixSupportedDeliveryTypes[$this->asset->assetType][$this->asset->deliveryType])) {
                $message = "URL Suffix is only supported in {$this->asset->assetType} " .
                           implode(
                               ',',
                               array_keys(ArrayUtils::get($suffixSupportedDeliveryTypes, [$this->asset->assetType], []))
                           );
                $this->getLogger()->critical(
                    $message,
                    [
                        'suffix'       => $this->asset->suffix,
                        'assetType'    => $this->asset->assetType,
                        'deliveryType' => $this->asset->deliveryType,
                    ]
                );
                throw new UnexpectedValueException($message);
            }

            $finalAssetType = $suffixSupportedDeliveryTypes[$this->asset->assetType][$this->asset->deliveryType];
        } else {
            $finalAssetType = implode('/', [$this->asset->assetType, $this->asset->deliveryType]);
        }

        return $finalAssetType;
    }

    /**
     * Finalizes asset source.
     *
     * @return mixed
     */
    protected function finalizeSource()
    {
        $source = $this->asset->publicId(true);

        if (! preg_match('/^https?:\//i', $source)) {
            $source = rawurldecode($source);
        }

        $source = StringUtils::smartEscape($source);

        if (! empty($this->asset->suffix)) {
            $source = "{$source}/{$this->asset->suffix}";
        }
        if (! empty($this->asset->extension)) {
            $source = "{$source}.{$this->asset->extension}";
        }

        return $source;
    }

    /**
     * Finalizes version part of the asset URL.
     *
     * @return mixed
     */
    protected function finalizeVersion()
    {
        $version = $this->asset->version;

        if (empty($version) && $this->urlConfig->forceVersion
            && ! empty($this->asset->location)
            && ! preg_match("/^https?:\//", $this->asset->publicId())
            && ! preg_match('/^v\d+/', $this->asset->publicId())
        ) {
            $version = '1';
        }

        return $version ? 'v' . $version : null;
    }

    /**
     * Finalizes URL signature.
     *
     * @see https://cloudinary.com/documentation/advanced_url_delivery_options#generating_delivery_url_signatures
     *
     * @return string
     * @throws ConfigurationException
     */
    protected function finalizeSimpleSignature()
    {
        if (! $this->urlConfig->signUrl || $this->authToken->isEnabled()) {
            return '';
        }

        if (empty($this->cloud->apiSecret)) {
            throw new ConfigurationException('Must supply apiSecret');
        }

        $toSign    = $this->asset->publicId();
        $signature = StringUtils::base64UrlEncode(
            Utils::sign(
                $toSign,
                $this->cloud->apiSecret,
                true,
                $this->getSignatureAlgorithm()
            )
        );

        return Utils::formatSimpleSignature(
            $signature,
            $this->urlConfig->longUrlSignature ? Utils::LONG_URL_SIGNATURE_LENGTH : Utils::SHORT_URL_SIGNATURE_LENGTH
        );
    }

    /**
     * Check if passed signatureAlgorithm is supported otherwise return SHA1.
     *
     * @return string
     */
    protected function getSignatureAlgorithm()
    {
        if ($this->urlConfig->longUrlSignature) {
            return Utils::ALGO_SHA256;
        }

        if (ArrayUtils::inArrayI($this->cloud->signatureAlgorithm, Utils::SUPPORTED_SIGNATURE_ALGORITHMS)) {
            return $this->cloud->signatureAlgorithm;
        }

        return Utils::ALGO_SHA1;
    }

    /**
     * Finalizes URL.
     *
     * @param string $urlStr The URL to finalize.
     *
     * @return string|UriInterface The resulting URL.
     */
    protected function finalizeUrl($urlStr)
    {
        $urlParts = parse_url($urlStr);

        $urlParts = $this->finalizeUrlWithAuthToken($urlParts);
        $urlParts = $this->finalizeUrlWithAnalytics($urlParts);

        return Uri::fromParts($urlParts);
    }

    /**
     * Finalizes URL signature, when AuthToken is used.
     *
     * @param array $urlParts The URL parts to sign.
     *
     * @return array resulting URL parts
     */
    protected function finalizeUrlWithAuthToken($urlParts)
    {
        if (! $this->urlConfig->signUrl || ! $this->authToken->isEnabled()) {
            return $urlParts;
        }

        $token = $this->authToken->generate($urlParts['path']);

        $urlParts['query'] = ArrayUtils::implodeAssoc(
            ArrayUtils::mergeNonEmpty(
                StringUtils::parseQueryString(ArrayUtils::get($urlParts, 'query')),
                StringUtils::parseQueryString($token)
            ),
            '='
        );

        return $urlParts;
    }

    /**
     * Finalizes URL with analytics data.
     *
     * @param array $urlParts The URL to add analytics to.
     *
     * @return array resulting URL
     */
    protected function finalizeUrlWithAnalytics($urlParts)
    {
        if (! $this->urlConfig->analytics) {
            return $urlParts;
        }

        // Disable analytics for public IDs containing query params.
        if (! empty($urlParts['query']) || StringUtils::contains($this->asset->publicId(), "?")) {
            return $urlParts;
        }

        $urlParts['query'] = ArrayUtils::implodeAssoc(
            ArrayUtils::mergeNonEmpty(
                StringUtils::parseQueryString(ArrayUtils::get($urlParts, 'query')),
                [Analytics::QUERY_KEY, Analytics::sdkAnalyticsSignature()]
            ),
            '='
        );

        return $urlParts;
    }
}
