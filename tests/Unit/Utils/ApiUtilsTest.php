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
use PHPUnit\Framework\TestCase;

/**
 * Class ApiUtilsTest
 */
final class ApiUtilsTest extends TestCase
{
    /**
     * @return array
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
     * Verifies that different headers are correctly serialized
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
}
