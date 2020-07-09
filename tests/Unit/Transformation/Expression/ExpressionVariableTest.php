<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Transformation\Expression;

use Cloudinary\Transformation\Expression\PVar;
use Cloudinary\Transformation\Expression\UVar;
use Cloudinary\Transformation\Variable\Variable;
use PHPUnit\Framework\TestCase;

/**
 * Class ExpressionVariableTest
 */
final class ExpressionVariableTest extends TestCase
{
    public function testUserVariable()
    {
        $customVariable = new Variable('var', 100);

        self::assertEquals('$var', (string)new UVar('$var'));
        self::assertEquals('$var', (string)new UVar('var'));
        self::assertEquals('$var', (string)new UVar($customVariable));
        self::assertEquals('$var', (string)UVar::uVar('var'));
    }

    public function testPredefinedVariable()
    {
        self::assertEquals('iw', (string)new PVar(PVar::INITIAL_WIDTH));
        self::assertEquals('iw', (string)new PVar('iw'));
        self::assertEquals('iw', (string)PVar::initialWidth());
        self::assertEquals('du', (string)PVar::duration());
        self::assertEquals('idu', (string)PVar::initialDuration());
    }
}
