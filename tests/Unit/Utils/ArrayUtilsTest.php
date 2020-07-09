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

use Cloudinary\ArrayUtils;
use PHPUnit\Framework\TestCase;

/**
 * Class ArrayUtilsTest
 */
final class ArrayUtilsTest extends TestCase
{
    public function testSortByArray()
    {
        $array = ['y' => 'y', 'z' => 'z', 'b' => 'b', 'a' => 'a'];

        $sortedArray = ['a' => 'a', 'b' => 'b', 'y' => 'y', 'z' => 'z'];

        $testOrder = ['z', 'b', 'y', 'a'];
        $this->assertSame(
            ['z' => 'z', 'b' => 'b', 'y' => 'y', 'a' => 'a'],
            ArrayUtils::sortByArray($array, $testOrder)
        );

        $testOrderExtra = ['z', 'b', 'y', 'a', 'c', 'x'];
        $this->assertSame(
            ['z' => 'z', 'b' => 'b', 'y' => 'y', 'a' => 'a'],
            ArrayUtils::sortByArray($array, $testOrderExtra)
        );

        $testOrderMissing = ['z', 'b'];
        $this->assertSame(
            ['z' => 'z', 'b' => 'b', 'a' => 'a', 'y' => 'y'],
            ArrayUtils::sortByArray($array, $testOrderMissing)
        );

        $testOrderMissingWithExtra = ['z', 'b', 'c', 'x'];
        $this->assertSame(
            ['z' => 'z', 'b' => 'b', 'a' => 'a', 'y' => 'y'],
            ArrayUtils::sortByArray($array, $testOrderMissingWithExtra)
        );

        $testEmptyOrder = [];
        $this->assertSame(
            $sortedArray,
            ArrayUtils::sortByArray($array, $testEmptyOrder)
        );

        $testIrrelevantOrder = ['i', 'j', 'k'];
        $this->assertSame(
            $sortedArray,
            ArrayUtils::sortByArray($array, $testIrrelevantOrder)
        );
    }
}
