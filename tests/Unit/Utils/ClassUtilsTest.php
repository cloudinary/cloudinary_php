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

use Cloudinary\ClassUtils;
use Cloudinary\Test\Unit\TestHelpers\TestClassA;
use Cloudinary\Test\Unit\TestHelpers\TestClassB;
use PHPUnit\Framework\TestCase;

/**
 * Class ClassUtilsTest
 */
final class ClassUtilsTest extends TestCase
{
    public function testVerifyInstance()
    {
        $this->assertEquals(
            null,
            ClassUtils::verifyInstance(null, TestClassA::class)
        );
        $this->assertEquals(
            TestClassA::class,
            get_class(ClassUtils::verifyInstance(new TestClassA(), TestClassA::class, TestClassB::class))
        );
        $this->assertEquals(
            TestClassB::class,
            get_class(ClassUtils::verifyInstance(new TestClassB(), TestClassA::class))
        );
        $this->assertEquals(
            TestClassA::class,
            get_class(ClassUtils::verifyInstance(20, TestClassA::class))
        );
        $this->assertEquals(
            TestClassB::class,
            get_class(ClassUtils::verifyInstance(20, TestClassA::class, TestClassB::class))
        );
        $this->assertEquals(
            TestClassA::class,
            get_class(ClassUtils::verifyInstance(new TestClassA(), TestClassA::class))
        );
        $this->assertEquals(
            'TestClassA(20)',
            (string)(ClassUtils::verifyInstance(20, TestClassA::class))
        );
        $this->assertEquals(
            'TestClassA(20, 30)',
            (string)(ClassUtils::verifyInstance(20, TestClassA::class, null, 30))
        );
    }

    // TODO: add tests for all ClassUtils functions
}
