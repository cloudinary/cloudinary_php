<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Asset;

use Cloudinary\Asset\Image;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Configuration\UrlConfig;

/**
 * Class DistributionTest
 */
final class DistributionTest extends AssetTestCase
{
    const EXPECTED_SHARD = 2;

    /**
     * @var Image $image Test image that is commonly reused by tests
     */
    protected $image;

    public function setUp()
    {
        parent::setUp();

        $this->image = new Image(self::IMAGE_NAME);
    }

    /**
     * Should use default secure distribution if secure=true
     */
    public function testSecureDistribution()
    {
        self::assertImageUrl(self::IMAGE_NAME, $this->image->secure(), ['protocol' => self::PROTOCOL_HTTPS]);
        self::assertImageUrl(self::IMAGE_NAME, $this->image->secure(false), ['protocol' => self::PROTOCOL_HTTP]);
    }

    /**
     * Should use default secure distribution if secure is set in config
     */
    public function testSecureDistributionFromConfig()
    {
        Configuration::instance()->url->secure();

        self::assertImageUrl(self::IMAGE_NAME, new Image(self::IMAGE_NAME), ['protocol' => self::PROTOCOL_HTTPS]);

        Configuration::instance()->url->secure(false);

        self::assertImageUrl(self::IMAGE_NAME, new Image(self::IMAGE_NAME), ['protocol' => self::PROTOCOL_HTTP]);
    }

    /**
     * Should allow overwriting secure distribution if secure=TRUE
     */
    public function testSecureDistributionOverwrite()
    {
        $host = 'something.else.com';

        self::assertImageUrl(
            self::IMAGE_NAME,
            $this->image->secure()->secureCname($host),
            ['protocol' => self::PROTOCOL_HTTPS, 'hostname' => $host]
        );
    }

    /**
     * Should take secure distribution from config if secure=true
     */
    public function testSecureDistributionHostFromConfig()
    {
        $host = 'config.secure.distribution.com';

        Configuration::instance()->url->secureCname($host);

        self::assertImageUrl(
            self::IMAGE_NAME,
            (new Image(self::IMAGE_NAME))->secure(),
            ['protocol' => self::PROTOCOL_HTTPS, 'hostname' => $host]
        );
    }

    /**
     * Should default to akamai if secure is given with private_cdn and no secure_distribution
     */
    public function testSecureAkamai()
    {
        self::assertImageUrl(
            self::IMAGE_NAME,
            $this->image->secure()->privateCdn(),
            [
                'protocol'   => self::PROTOCOL_HTTPS,
                'hostname'   => self::CLOUD_NAME . '-' . UrlConfig::DEFAULT_SHARED_HOST,
                'cloud_name' => null,
            ]
        );
    }

    /**
     * Should not add cloud_name if private_cdn and secure non akamai secure_distribution
     */
    public function testSecureNonAkamai()
    {
        $host = 'something.cloudfront.net';

        self::assertImageUrl(
            self::IMAGE_NAME,
            $this->image->secure()->privateCdn()->secureCname($host),
            [
                'protocol'   => self::PROTOCOL_HTTPS,
                'hostname'   => $host,
                'cloud_name' => null,
            ]
        );
    }

    /**
     * Should not add cloud_name if private_cdn and not secure
     */
    public function testHttpPrivateCdn()
    {
        self::assertImageUrl(
            self::IMAGE_NAME,
            $this->image->privateCdn(),
            [
                'hostname'   => self::CLOUD_NAME . '-' . UrlConfig::DEFAULT_SHARED_HOST,
                'cloud_name' => null,
            ]
        );
    }

    /**
     * Should support cdn_subdomain with secure on if using shared_domain
     */
    public function testSecureSharedSubDomain()
    {
        self::assertImageUrl(
            self::IMAGE_NAME,
            $this->image->cdnSubdomain()->secure(),
            [
                'protocol' => self::PROTOCOL_HTTPS,
                'hostname' => UrlConfig::DEFAULT_SUB_DOMAIN . '-' . self::EXPECTED_SHARD . '.' .
                              UrlConfig::DEFAULT_DOMAIN,
            ]
        );
    }

    /**
     * Should support secure_cdn_subdomain false override with secure
     */
    public function testSecureSharedSubDomainFalse()
    {
        self::assertImageUrl(
            self::IMAGE_NAME,
            $this->image->cdnSubdomain()->secure()->secureCdnSubdomain(false),
            [
                'protocol' => self::PROTOCOL_HTTPS,
            ]
        );
    }

    /**
     * Should support secure_cdn_subdomain true override with secure
     */
    public function testSecureSubDomainTrue()
    {
        self::assertImageUrl(
            self::IMAGE_NAME,
            $this->image->cdnSubdomain()->secure()->secureCdnSubdomain()->privateCdn(),
            [
                'protocol'   => self::PROTOCOL_HTTPS,
                'hostname'   => self::CLOUD_NAME . '-' . UrlConfig::DEFAULT_SUB_DOMAIN . '-' . self::EXPECTED_SHARD .
                                '.' . UrlConfig::DEFAULT_DOMAIN,
                'cloud_name' => null,
            ]
        );
    }

    /**
     * Should support external cname
     */
    public function testCName()
    {
        self::assertImageUrl(
            self::IMAGE_NAME,
            $this->image->cname(self::TEST_HOSTNAME)->secure(false),
            [
                'hostname' => self::TEST_HOSTNAME,
                'protocol' => 'http',
            ]
        );
    }

    /**
     * Should support external cname with cdn_subdomain on
     */
    public function testCNameSubDomain()
    {
        self::assertImageUrl(
            self::IMAGE_NAME,
            $this->image->cname(self::TEST_HOSTNAME)->cdnSubdomain()->secure(false),
            [
                'hostname' => 'a' . self::EXPECTED_SHARD . '.' . self::TEST_HOSTNAME,
                'protocol' => 'http',
            ]
        );
    }

    public function testSignature()
    {
        self::assertImageUrl('s--MDvxhRxa--/' . self::IMAGE_NAME, $this->image->signUrl());
    }

    /**
     * Should support long url signature
     */
    public function testLongSignature()
    {
        $this->image->urlConfig->signUrl = true;
        $this->image->urlConfig->longUrlSignature = true;

        self::assertImageUrl('s--RVsT3IpYGITMIc0RjCpde9T9Uujc2c1X--/' . self::IMAGE_NAME, $this->image);
    }

    public function testForceVersion()
    {
        self::assertImageUrl(
            self::DEFAULT_ASSET_VERSION_STR . '/' . self::IMAGE_IN_FOLDER,
            new Image(self::IMAGE_IN_FOLDER)
        );

        self::assertImageUrl(
            self::IMAGE_IN_FOLDER,
            (new Image(self::IMAGE_IN_FOLDER))->forceVersion(false)
        );
    }
}
