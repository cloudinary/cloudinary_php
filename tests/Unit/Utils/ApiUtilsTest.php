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

/**
 * Class ApiUtilsTest
 */
final class ApiUtilsTest extends UnitTestCase
{
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
     * Verifies that context data is correctly serialized.
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
}
