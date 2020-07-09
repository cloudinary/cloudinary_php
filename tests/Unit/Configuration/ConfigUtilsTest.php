<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Configuration;

use Cloudinary\Configuration\ConfigUtils;
use Cloudinary\Test\Unit\UnitTestCase;
use InvalidArgumentException;
use UnexpectedValueException;

/**
 * Class CloudinaryUtilsTest
 */
final class ConfigUtilsTest extends UnitTestCase
{
    public function testParseSimpleCloudinaryUrl()
    {
        $configArr = ConfigUtils::parseCloudinaryUrl('cloudinary://' . self::CLOUD_NAME);

        $this->assertCount(1, $configArr['account']);
        $this->assertEquals(self::CLOUD_NAME, $configArr['account']['cloud_name']);
    }

    public function testParseCloudinaryUrl()
    {
        $configArr = ConfigUtils::parseCloudinaryUrl($this->cloudinaryUrl);

        $this->assertCount(3, $configArr['account']);
        $this->assertEquals(self::CLOUD_NAME, $configArr['account']['cloud_name']);
        $this->assertEquals(self::API_KEY, $configArr['account']['api_key']);
        $this->assertEquals(self::API_SECRET, $configArr['account']['api_secret']);
    }

    public function testParseCloudinaryUrlSecureDistribution()
    {
        $configArr = ConfigUtils::parseCloudinaryUrl($this->cloudinaryUrl . '/' . $this::SECURE_DIST);

        $this->assertCount(3, $configArr['account']);

        $this->assertCount(2, $configArr['url']);

        $this->assertTrue($configArr['url']['private_cdn']);
        $this->assertEquals(self::SECURE_DIST, $configArr['url']['secure_distribution']);
    }

    public function testParseCloudinaryUrlNestedValues()
    {
        $configArr = ConfigUtils::parseCloudinaryUrl("$this->cloudinaryUrl?foo[bar]=value&foo[baz][qux]=quxval");

        $this->assertArrayHasKey('foo', $configArr);
        $this->assertArrayHasKey('bar', $configArr['foo']);
        $this->assertEquals('value', $configArr['foo']['bar']);
        $this->assertEquals('quxval', $configArr['foo']['baz']['qux']);
    }

    public function testParseEmptyCloudinaryUrl()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->assertEquals([], ConfigUtils::parseCloudinaryUrl(null));
    }

    public function testParseInvalidCloudinaryUrlScheme()
    {
        $this->expectException(UnexpectedValueException::class);
        ConfigUtils::parseCloudinaryUrl("not$this->cloudinaryUrl");
    }

    public function testParseInvalidCloudinaryUrl()
    {
        $this->expectException(UnexpectedValueException::class);
        ConfigUtils::parseCloudinaryUrl('cloudinary:///' . self::CLOUD_NAME); // parse_url will return false
    }

    public function testParseBooleansInCloudinaryUrl()
    {
        $configArr = ConfigUtils::parseCloudinaryUrl(
            "$this->cloudinaryUrl?logging[enabled]=fALse&logging[nested][bool]=tRUe"
        );

        $this->assertArrayHasKey('logging', $configArr);
        $this->assertArrayHasKey('enabled', $configArr['logging']);
        $this->assertArrayHasKey('nested', $configArr['logging']);
        $this->assertArrayHasKey('bool', $configArr['logging']['nested']);
        $this->assertEquals(false, $configArr['logging']['enabled']);
        $this->assertEquals(true, $configArr['logging']['nested']['bool']);
    }

    public function testParseLoggerConfigFromCloudinaryUrl()
    {
        $configArr = ConfigUtils::parseCloudinaryUrl(
            $this->cloudinaryUrl . '?logging[level]=CRITICAL&logging[file][my_debug_file][path]=logs/cloudinary.log' .
            '&logging[file][my_debug_file][level]=debug&logging[file][file_for_critical_logs][path]=' .
            'logs/cloudinary_critical.log&logging[error_log]=[]'
        );

        $expectedLoggerConfig = [
            'level' => 'CRITICAL',
            'file' => [
                'my_debug_file' => [
                    'path' => 'logs/cloudinary.log',
                    'level' => 'debug',
                ],
                'file_for_critical_logs' => [
                    'path' => 'logs/cloudinary_critical.log',
                ],
            ],
            'error_log' => [],
        ];

        self::assertEquals($expectedLoggerConfig, $configArr['logging']);
    }
}
