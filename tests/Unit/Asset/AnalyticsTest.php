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
use Cloudinary\Asset\Image;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Test\Helpers\MockAnalytics;
use OutOfRangeException;

/**
 * Class AnalyticsTest
 */
final class AnalyticsTest extends AssetTestCase
{
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

    public function testSdkAnalyticsSignatureWithIntegration()
    {
        MockAnalytics::sdkCode('W');
        MockAnalytics::sdkVersion('2.0.0');
        MockAnalytics::techVersion('9.5');

        self::assertEquals(
            'AWAACH9',
            MockAnalytics::sdkAnalyticsSignature()
        );
    }
}
