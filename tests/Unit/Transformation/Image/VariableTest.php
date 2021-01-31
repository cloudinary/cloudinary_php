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

use Cloudinary\Transformation\Expression\PVar;
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
        self::assertEquals('$var_100', (string)Variable::set('var', 100));
        self::assertEquals('$var_100_to_f', (string)Variable::set('var', 100)->asFloat());
        self::assertEquals('$var_100_to_i', (string)Variable::set('var', 100)->asInteger());
        self::assertEquals('$var_!100!', (string)Variable::set('var', '100'));
        self::assertEquals('$var_iw', (string)Variable::set('var', PVar::initialWidth()));
        self::assertEquals('$var_!apple:orange:kiwi!', (string)Variable::set('var', ['apple', 'orange', 'kiwi']));
        self::assertEquals('$var_ref:!asset!', (string)Variable::setAssetReference('var', 'asset'));
        self::assertEquals('$var_ctx:!ctx_key!', (string)Variable::setFromContext('var', 'ctx_key'));
        self::assertEquals('$var_md:!md_key!', (string)Variable::setFromMetadata('var', 'md_key'));
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
