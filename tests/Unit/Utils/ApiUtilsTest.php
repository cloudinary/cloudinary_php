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
    public function tearDown()
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
                'result' => 'bbdc631f4b490c0ba65722d8dbf9300d1fd98e86',
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
                'result' => 'bbdc631f4b490c0ba65722d8dbf9300d1fd98e86',
                'signatureAlgorithm' => 'sha1',
            ],
            [
                'value'  => ['p1' => 'v1=v2*|}{ & !@#$%^&*()_;/.,?><\\/|_+a'],
                'result' => '9cdbdd04f587b41db72d66437f6dac2a379cd899c0cf3c3430925b1beca6052d',
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
            'cloud_name' => 'dn6ot3ged',
            'timestamp' => 1568810420,
            'username' => 'user@cloudinary.com'
        ];

        $params = $initialParams;
        Configuration::instance()->cloud->apiSecret = 'hdcixPpR2iKERPwqvH6sHdK9cyac';
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
            'cloud_name' => 'dn6ot3ged',
            'timestamp' => 1568810420,
            'username' => 'user@cloudinary.com'
        ];

        $config = new Configuration('cloudinary://key:hdcixPpR2iKERPwqvH6sHdK9cyac@test123');
        $config->cloud->signatureAlgorithm = Utils::ALGO_SHA256;
        ApiUtils::signRequest($params, $config->cloud);
        $expected = '45ddaa4fa01f0c2826f32f669d2e4514faf275fe6df053f1a150e7beae58a3bd';
        self::assertEquals($expected, $params['signature']);
    }
}
