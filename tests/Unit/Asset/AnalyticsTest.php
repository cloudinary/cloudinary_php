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

use Cloudinary\Asset\Analytics;
use Cloudinary\Asset\DeliveryType;
use Cloudinary\Asset\Image;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Test\Helpers\MockAnalytics;
use OutOfRangeException;

/**
 * Class AnalyticsTest
 */
final class AnalyticsTest extends AssetTestCase
{
    const AUTH_TOKEN_KEY = '00112233FF99';

    public function testEncodeVersion()
    {
        self::assertStrEquals(
            'Alh',
            self::invokeNonPublicMethod(MockAnalytics::class, 'encodeVersion', '1.24.0')
        );

        self::assertEquals(
            'AM',
            self::invokeNonPublicMethod(MockAnalytics::class, 'encodeVersion', '12.0')
        );
        self::assertEquals(
            '///',
            self::invokeNonPublicMethod(MockAnalytics::class, 'encodeVersion', '43.21.26')
        );
        self::assertEquals(
            'AAA',
            self::invokeNonPublicMethod(MockAnalytics::class, 'encodeVersion', '0.0.0')
        );
    }

    public function testEncodeInvalidVersion()
    {
        $this->expectException(OutOfRangeException::class);
        self::invokeNonPublicMethod(MockAnalytics::class, 'encodeVersion', '44.45.46');
    }

    public function testSdkAnalyticsSignature()
    {
        self::assertEquals(
            'AAJ1uAI',
            MockAnalytics::sdkAnalyticsSignature()
        );
    }

    public function testAssetWithAnalytics()
    {
        $config = new Configuration(Configuration::instance());

        $config->url->analytics();

        self::assertContains(
            '?'. Analytics::QUERY_KEY. '=',
            (string)new Image(self::ASSET_ID, $config)
        );
    }

    /**
     * Disable analytics if public IDs contain query parameters ?key=value.
     */
    public function testUrlNoAnalyticsWithQueryParams()
    {
        $config = new Configuration(Configuration::instance());
        $config->url->analytics();
        $config->url->privateCdn();
        $config->url->signUrl();
        $config->authToken->key = self::AUTH_TOKEN_KEY;
        $config->authToken->startTime = 11111111;
        $config->authToken->duration = 300;

        $img = new Image('test', $config);
        $img->asset->deliveryType = DeliveryType::AUTHENTICATED;

        self::assertEquals(
            'https://test123-res.cloudinary.com/image/authenticated/test?__cld_token__' .
            '=st=11111111~exp=11111411~hmac=735a49389a72ac0b90d1a84ac5d43facd1a9047f153b39e914747ef6ed195e53',
            (string)$img
        );
    }
}
