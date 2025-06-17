<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Utils;

use Cloudinary\Api\ApiUtils;
use Cloudinary\Test\Unit\UnitTestCase;
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\Transformation;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Utils;

/**
 * Class ApiUtilsTest
 */
final class ApiUtilsTest extends UnitTestCase
{
    public const API_SIGN_REQUEST_TEST_SECRET = 'hdcixPpR2iKERPwqvH6sHdK9cyac';
    public const API_SIGN_REQUEST_CLOUD_NAME = 'dn6ot3ged';

    public function tearDown(): void
    {
        parent::tearDown();

        Configuration::instance()->init();
    }

    /**
     * Data provider for the method `testSerializeArrayOfArrays()`.
     *
     * @return array[]
     */
    public function dataProviderSerializeArrayOfArrays()
    {
        return [
            [
                'value'  => '',
                'result' => '',
            ],
            [
                'value'  => null,
                'result' => '',
            ],
            [
                'value'  => 0,
                'result' => '0',
            ],
            [
                'value'  => [],
                'result' => '',
            ],
            [
                'value'  => [[], []],
                'result' => '',
            ],
            [
                'value'  => [['1', 0], [1, '0'], ['value']],
                'result' => '1,0|1,0|value',
            ],
            [
                'value'  => [[0]],
                'result' => '0',
            ],
        ];
    }

    /**
     * Data provider for the method `testSerializeHeaders()`.
     *
     * @return array[]
     */
    public function dataProviderSerializeHeaders()
    {
        return [
            [
                'value'  => ['Authorization' => 'Basic YWxhZGRpbjpvcGVuc2VzYW1l'],
                'result' => 'Authorization:Basic YWxhZGRpbjpvcGVuc2VzYW1l',
            ],
            [
                'value'  => ['Authorization: Basic FvFxhZGRpbjpvcGVuc2VzYW1l'],
                'result' => 'Authorization: Basic FvFxhZGRpbjpvcGVuc2VzYW1l',
            ],
            [
                'value'  => ['X-Robots-Tag' => 'noindex'],
                'result' => 'X-Robots-Tag:noindex',
            ],
            [
                'value'  => ['X-Robots-Tag: noindex'],
                'result' => 'X-Robots-Tag: noindex',
            ],
            [
                'value'  => ['Link' => 1],
                'result' => 'Link:1',
            ],
            [
                'value'  => ['Link: 1'],
                'result' => 'Link: 1',
            ],
        ];
    }

    /**
     * Verifies that different headers are correctly serialized.
     *
     * @param $value
     * @param $result
     *
     * @dataProvider dataProviderSerializeHeaders
     */
    public function testSerializeHeaders($value, $result)
    {
        self::assertEquals(
            $result,
            ApiUtils::serializeHeaders($value)
        );
    }

    /**
     * Test serialization of an array with nested arrays.
     *
     * @param $value
     * @param $result
     *
     * @dataProvider dataProviderSerializeArrayOfArrays
     */
    public function testSerializeArrayOfArrays($value, $result)
    {
        self::assertEquals(
            $result,
            ApiUtils::serializeArrayOfArrays($value)
        );
    }

    /**
     * Verifies that an asset transformations is correctly serialized.
     */
    public function testTransformationComplexExpressions()
    {
        $transformation1 = new Transformation();
        $transformation1->scale(3204);

        $transformation2 = new Transformation();
        $transformation2->rotate(127);
        $transformation2->format(Format::jpg());

        self::assertEquals(
            'c_scale,w_3204|a_127/f_jpg',
            ApiUtils::serializeAssetTransformations([$transformation1, $transformation2])
        );
    }

    /**
     * Data provider for the method `testSerializeContext()`.
     *
     * @return array[]
     */
    public function dataProviderSerializeContext()
    {
        return [
            [
                'value'  => '',
                'result' => '',
            ],
            [
                'value'  => null,
                'result' => '',
            ],
            [
                'value'  => 0,
                'result' => '0',
            ],
            [
                'value'  => [],
                'result' => '',
            ],
            [
                'value'  => ['!@#$%^&?*-+=[]{}()' => '!@#$%^&?*-+=[]{}()'],
                'result' => '!@#$%^&?*-+\=[]{}()=!@#$%^&?*-+\=[]{}()',
            ],
            [
                'value'  => ['caption' => 'cap=caps', 'alt' => 'alternative|alt=a'],
                'result' => 'caption=cap\=caps|alt=alternative\|alt\=a',
            ],
            [
                'value'  => ['caption' => ['cap1', 'cap2'], 'alt' => ['a|"a"', 'b="b"']],
                'result' => 'caption=["cap1","cap2"]|alt=["a\|\"a\"","b\=\"b\""]',
            ],
        ];
    }

    /**
     * Verifies that context data is correctly serialized.
     *
     * @param $value
     * @param $result
     *
     * @dataProvider dataProviderSerializeContext
     */
    public function testSerializeContext($value, $result)
    {
        self::assertEquals(
            $result,
            ApiUtils::serializeContext($value)
        );
    }

    /**
     * Data provider for the method `testSignParameters()`.
     *
     * @return array[]
     */
    public function dataProviderSignParameters()
    {
        return [
            [
                'value'  => ['p1' => 'v1'],
                'result' => '4cdcfc973f12ab6a9a6ba56595a9c2897bdb8f32',
            ],
            [
                'value'  => ['p1' => 'v1,v2'],
                'result' => '9e06ad20c8a98319b00503edc4053be120017905',
            ],
            [
                'value'  => ['p1' => ['v1', 'v2']],
                'result' => '9e06ad20c8a98319b00503edc4053be120017905',
            ],
            [
                'value'  => ['p1' => 'v1=v2*|}{ & !@#$%^&*()_;/.,?><\\/|_+a'],
                'result' => 'ced1e363d8db0a8d7ebcfb9e67fadbf5ee78a0f1',
            ],
        ];
    }

    /**
     * Verifies that correct signature is produced based on parameters.
     *
     * @param $value
     * @param $result
     *
     * @dataProvider dataProviderSignParameters
     */
    public function testSignParameters($value, $result)
    {
        self::assertEquals(
            $result,
            ApiUtils::signParameters($value, self::API_SECRET)
        );
    }

    /**
     * Data provider for the method `testSignParametersWithExplicitSignatureAlgorithm()`.
     *
     * @return array[]
     */
    public function dataProviderSignWithAlgorithmParameters()
    {
        return [
            [
                'value'  => ['p1' => 'v1'],
                'result' => '4cdcfc973f12ab6a9a6ba56595a9c2897bdb8f32',
                'signatureAlgorithm' => 'SHA1',
            ],
            [
                'value'  => ['p1' => 'v1,v2'],
                'result' => '9e06ad20c8a98319b00503edc4053be120017905',
                'signatureAlgorithm' => 'sha1',
            ],
            [
                'value'  => ['p1' => ['v1', 'v2']],
                'result' => '9e06ad20c8a98319b00503edc4053be120017905',
                'signatureAlgorithm' => 'sha1',
            ],
            [
                'value'  => ['p1' => 'v1=v2*|}{ & !@#$%^&*()_;/.,?><\\/|_+a'],
                'result' => 'ced1e363d8db0a8d7ebcfb9e67fadbf5ee78a0f1',
                'signatureAlgorithm' => 'sha1',
            ],
            [
                'value'  => ['p1' => 'v1=v2*|}{ & !@#$%^&*()_;/.,?><\\/|_+a'],
                'result' => '0c06416c30bfc727eb2cbc9f93245be70bd6567c788b5bd93a3772e8253312bf',
                'signatureAlgorithm' => 'sha256',
            ],
        ];
    }

    /**
     * Verifies that correct signature is produced based on parameters using an explicit algorithm.
     *
     * @param $value
     * @param $result
     * @param $signatureAlgorithm
     *
     * @dataProvider dataProviderSignWithAlgorithmParameters
     */
    public function testSignParametersWithExplicitSignatureAlgorithm($value, $result, $signatureAlgorithm)
    {
        self::assertEquals(
            $result,
            ApiUtils::signParameters($value, self::API_SECRET, $signatureAlgorithm)
        );
    }

    /**
     * Verifies that correct sha256 and sha1 hashes are produced based on signature algorithm from global config.
     */
    public function testApiSignRequestWithGlobalConfig()
    {
        $initialParams = [
            'cloud_name' => self::API_SIGN_REQUEST_CLOUD_NAME,
            'timestamp' => 1568810420,
            'username' => 'user@cloudinary.com'
        ];

        $params = $initialParams;
        Configuration::instance()->cloud->apiSecret = self::API_SIGN_REQUEST_TEST_SECRET;
        Configuration::instance()->cloud->signatureAlgorithm = Utils::ALGO_SHA256;
        ApiUtils::signRequest($params, Configuration::instance()->cloud);
        $expected = '45ddaa4fa01f0c2826f32f669d2e4514faf275fe6df053f1a150e7beae58a3bd';
        self::assertEquals($expected, $params['signature']);

        $params = $initialParams;
        Configuration::instance()->cloud->signatureAlgorithm = null;
        ApiUtils::signRequest($params, Configuration::instance()->cloud);
        $expectedSha1 = '14c00ba6d0dfdedbc86b316847d95b9e6cd46d94';
        self::assertEquals($expectedSha1, $params['signature']);
    }

    /**
     * Verifies that correct sha256 and sha1 hashes are produced based on explicitly passed signature algorithm.
     */
    public function testApiSignRequestWithExplicitConfig()
    {
        $params = [
            'cloud_name' => self::API_SIGN_REQUEST_CLOUD_NAME,
            'timestamp' => 1568810420,
            'username' => 'user@cloudinary.com'
        ];

        $config = new Configuration('cloudinary://key:' . self::API_SIGN_REQUEST_TEST_SECRET . '@test123');
        $config->cloud->signatureAlgorithm = Utils::ALGO_SHA256;
        ApiUtils::signRequest($params, $config->cloud);
        $expected = '45ddaa4fa01f0c2826f32f669d2e4514faf275fe6df053f1a150e7beae58a3bd';
        self::assertEquals($expected, $params['signature']);
    }

    /**
     * Should prevent parameter smuggling via & characters in parameter values.
     */
    public function testApiSignRequestPreventsParameterSmuggling()
    {
        // Test with notification_url containing & characters
        $paramsWithAmpersand = [
            'cloud_name' => self::API_SIGN_REQUEST_CLOUD_NAME,
            'timestamp' => 1568810420,
            'notification_url' => 'https://fake.com/callback?a=1&tags=hello,world'
        ];

        $config = new Configuration('cloudinary://key:' . self::API_SIGN_REQUEST_TEST_SECRET . '@test123');
        ApiUtils::signRequest($paramsWithAmpersand, $config->cloud);
        $signatureWithAmpersand = $paramsWithAmpersand['signature'];

        // Test that attempting to smuggle parameters by splitting the notification_url fails
        $paramsSmugggled = [
            'cloud_name' => self::API_SIGN_REQUEST_CLOUD_NAME,
            'timestamp' => 1568810420,
            'notification_url' => 'https://fake.com/callback?a=1',
            'tags' => 'hello,world'  // This would be smuggled if & encoding didn't work
        ];

        ApiUtils::signRequest($paramsSmugggled, $config->cloud);
        $signatureSmugggled = $paramsSmugggled['signature'];

        // The signatures should be different, proving that parameter smuggling is prevented
        self::assertNotEquals($signatureWithAmpersand, $signatureSmugggled,
                            'Signatures should be different to prevent parameter smuggling');

        // Verify the expected signature for the properly encoded case
        $expectedSignature = '4fdf465dd89451cc1ed8ec5b3e314e8a51695704';
        self::assertEquals($expectedSignature, $signatureWithAmpersand);

        // Verify the expected signature for the smuggled parameters case
        $expectedSmuggledSignature = '7b4e3a539ff1fa6e6700c41b3a2ee77586a025f9';
        self::assertEquals($expectedSmuggledSignature, $signatureSmugggled);
    }

    /**
     * Should apply the configured signature version from CloudConfig.
     */
    public function testConfiguredSignatureVersionIsApplied()
    {
        $params = [
            'cloud_name' => self::API_SIGN_REQUEST_CLOUD_NAME,
            'timestamp' => 1568810420,
            'notification_url' => 'https://fake.com/callback?a=1&tags=hello,world'
        ];

        $config = new Configuration('cloudinary://key:' . self::API_SIGN_REQUEST_TEST_SECRET . '@test123');

        // Test with signature version 1 (legacy behavior - no URL encoding)
        $config->cloud->signatureVersion = 1;
        $paramsV1 = $params;
        ApiUtils::signRequest($paramsV1, $config->cloud);
        $signatureV1 = $paramsV1['signature'];

        // Test with signature version 2 (current behavior - with URL encoding)
        $config->cloud->signatureVersion = 2;
        $paramsV2 = $params;
        ApiUtils::signRequest($paramsV2, $config->cloud);
        $signatureV2 = $paramsV2['signature'];

        // Signatures should be different, proving the version setting is applied
        self::assertNotEquals($signatureV1, $signatureV2,
                            'Signature versions should produce different results');

        // Version 2 should match the expected encoded signature
        $expectedV2Signature = '4fdf465dd89451cc1ed8ec5b3e314e8a51695704';
        self::assertEquals($expectedV2Signature, $signatureV2,
                          'Version 2 should match expected encoded signature');
    }
}
