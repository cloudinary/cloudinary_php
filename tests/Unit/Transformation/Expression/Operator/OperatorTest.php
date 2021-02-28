<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Transformation\Expression\Operator;

use Cloudinary\Transformation\Expression\ArithmeticOperator;
use Cloudinary\Transformation\Expression\LogicalOperator;
use Cloudinary\Transformation\Expression\RelationalOperator;
use Cloudinary\Transformation\Expression\StringRelationalOperator;
use PHPUnit\Framework\TestCase;

/**
 * Class OperatorTest
 */
final class OperatorTest extends TestCase
{
    public function testLogicalOperator()
    {
        self::assertEquals('and', (string)LogicalOperator::andOperator());
        self::assertEquals('and', (string)new LogicalOperator('&&'));

        self::assertEquals('or', (string)LogicalOperator::orOperator());
        self::assertEquals('or', (string)new LogicalOperator('||'));
    }

    public function testArithmeticOperator()
    {
        self::assertEquals('mul', (string)ArithmeticOperator::multiply());
        self::assertEquals('mul', (string)new ArithmeticOperator('*'));

        self::assertEquals('mod', (string)ArithmeticOperator::modulo());
        self::assertEquals('mod', (string)new ArithmeticOperator('%'));

        self::assertEquals(['operator' => 'mod'], (new ArithmeticOperator('%'))->jsonSerialize());
    }

    public function testRelationalOperator()
    {
        self::assertEquals('lt', (string)RelationalOperator::LessThan());
        self::assertEquals('lt', (string)new RelationalOperator('<'));
    }

    public function testStringRelationalOperator()
    {
        self::assertEquals('nin', (string)StringRelationalOperator::notIn());
        self::assertEquals('nin', (string)new StringRelationalOperator('nin'));
    }
}
