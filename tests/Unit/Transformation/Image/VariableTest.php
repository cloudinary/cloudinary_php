<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Transformation\Image;

use Cloudinary\Transformation\Variable\Variable;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Class VariableTest
 */
final class VariableTest extends TestCase
{
    public function testVariable()
    {
        self::assertEquals('$var_100', (string)new Variable('var', 100));
        self::assertEquals('$var_100', (string)Variable::define('var', 100));

        self::assertEquals('{"variable":{"name":"$var","value":100}}', json_encode(Variable::define('var', 100)));
    }

    public function testIsVariable()
    {
        self::assertTrue(Variable::isVariable('$a6'));
        self::assertTrue(Variable::isVariable('$a64534534'));
        self::assertTrue(Variable::isVariable('$ab'));
        self::assertTrue(Variable::isVariable('$asdasda'));
        self::assertTrue(Variable::isVariable('$a34asd12e'));
        self::assertTrue(Variable::isVariable('$a'));

        self::assertFalse(Variable::isVariable('sda'));
        self::assertFalse(Variable::isVariable('   '));
        self::assertFalse(Variable::isVariable('... . /'));
        self::assertFalse(Variable::isVariable('$'));
        self::assertFalse(Variable::isVariable('$4'));
        self::assertFalse(Variable::isVariable('$4dfds'));
        self::assertFalse(Variable::isVariable('$612s'));
        self::assertFalse(Variable::isVariable('$6 12s'));
        self::assertFalse(Variable::isVariable('$6 1.2s'));
    }

    public function testInvalidVariableName()
    {
        $this->expectException(InvalidArgumentException::class);
        new Variable('$17', 100);
    }
}
