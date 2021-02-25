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
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\Transformation;
use PHPUnit\Framework\TestCase;

/**
 * Class ApiUtilsTest
 */
final class ApiUtilsTest extends TestCase
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
                'value' => '',
                'result' => '',
            ],
            [
                'value' => null,
                'result' => '',
            ],
            [
                'value' => 0,
                'result' => '0',
            ],
            [
                'value' => [],
                'result' => '',
            ],
            [
                'value' => ['!@#$%^&?*-+=[]{}()' => '!@#$%^&?*-+=[]{}()'],
                'result' => '!@#$%^&?*-+\=[]{}()=!@#$%^&?*-+\=[]{}()',
            ],
            [
                'value' => ['caption' => 'cap=caps', 'alt' => 'alternative|alt=a'],
                'result' => 'caption=cap\=caps|alt=alternative\|alt\=a',
            ],
            [
                'value' => ['caption' => ['cap1', 'cap2'], 'alt' => ['a|"a"', 'b="b"']],
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
}
