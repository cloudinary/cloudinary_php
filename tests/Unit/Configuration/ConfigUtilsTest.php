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

        self::assertCount(1, $configArr['cloud']);
        self::assertEquals(self::CLOUD_NAME, $configArr['cloud']['cloud_name']);
    }

    public function testParseCloudinaryUrl()
    {
        $configArr = ConfigUtils::parseCloudinaryUrl($this->cloudinaryUrl);

        self::assertCount(3, $configArr['cloud']);
        self::assertEquals(self::CLOUD_NAME, $configArr['cloud']['cloud_name']);
        self::assertEquals(self::API_KEY, $configArr['cloud']['api_key']);
        self::assertEquals(self::API_SECRET, $configArr['cloud']['api_secret']);
    }

    public function testParseCloudinaryUrlSecureDistribution()
    {
        $configArr = ConfigUtils::parseCloudinaryUrl($this->cloudinaryUrl . '/' . $this::SECURE_CNAME);

        self::assertCount(3, $configArr['cloud']);

        self::assertCount(2, $configArr['url']);

        self::assertTrue($configArr['url']['private_cdn']);
        self::assertEquals(self::SECURE_CNAME, $configArr['url']['secure_cname']);
    }

    public function testParseCloudinaryUrlNestedValues()
    {
        $configArr = ConfigUtils::parseCloudinaryUrl("$this->cloudinaryUrl?foo[bar]=value&foo[baz][qux]=quxval");

        self::assertArrayHasKey('foo', $configArr);
        self::assertArrayHasKey('bar', $configArr['foo']);
        self::assertEquals('value', $configArr['foo']['bar']);
        self::assertEquals('quxval', $configArr['foo']['baz']['qux']);
    }

    public function testParseEmptyCloudinaryUrl()
    {
        $this->expectException(InvalidArgumentException::class);
        self::assertEquals([], ConfigUtils::parseCloudinaryUrl(null));
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

        self::assertArrayHasKey('logging', $configArr);
        self::assertArrayHasKey('enabled', $configArr['logging']);
        self::assertArrayHasKey('nested', $configArr['logging']);
        self::assertArrayHasKey('bool', $configArr['logging']['nested']);
        self::assertEquals(false, $configArr['logging']['enabled']);
        self::assertEquals(true, $configArr['logging']['nested']['bool']);
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
