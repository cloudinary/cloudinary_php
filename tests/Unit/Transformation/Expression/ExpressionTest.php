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

use Cloudinary\Transformation\Crop;
use Cloudinary\Transformation\Expression\Expression;
use Cloudinary\Transformation\Expression\ExpressionComponent;
use Cloudinary\Transformation\Expression\LogicalOperator;
use Cloudinary\Transformation\Expression\PVar;
use Cloudinary\Transformation\Expression\RelationalOperator;
use Cloudinary\Transformation\Expression\UVal;
use Cloudinary\Transformation\Expression\UVar;
use PHPUnit\Framework\TestCase;

/**
 * Class ExpressionTest
 */
final class ExpressionTest extends TestCase
{
    public function testArithmeticExpression()
    {
        $ae = PVar::initialAspectRatio()->add()->int(17);

        self::assertEquals('iar_add_17', (string)$ae);
    }

    public function testLogicalExpression()
    {
        $re = PVar::trimmedAspectRatio()->lessThanOrEqual()->int(17);

        $re_str = 'tar_lte_17';

        $le = new ExpressionComponent(
            new ExpressionComponent(
                $re,
                LogicalOperator::andOperator(),
                $re
            ),
            LogicalOperator::orOperator(),
            $re
        );


        self::assertEquals("{$re_str}_and_{$re_str}_or_{$re_str}", (string)$le);
    }

    public function testRelationalExpression()
    {
        $re = new ExpressionComponent(
            PVar::initialAspectRatio(),
            RelationalOperator::LessThanOrEqual(),
            UVal::float(17)
        );

        self::assertEquals('iar_lte_17', (string)$re);
    }

    public function testExpressionBuilder()
    {
        $addExpr = UVal::int(17)->add()->width()->lessThan()->faceCount()->multiply()->numeric(1.1);

        self::assertEquals('17_add_w_lt_fc_mul_1.1', (string)$addExpr);
    }

    public function testVariableExpression()
    {
        $addExpr = UVal::int(17)->add()->width();

        self::assertEquals('17_add_w', (string)$addExpr);

        $moduloExpr = UVar::uVar('myVar')->modulo()->generic('w');

        self::assertEquals('$myVar_mod_w', (string)$moduloExpr);

        $expr1 = UVar::uVar('myVar')->equal()->height()
                     ->and_()->width()->lessThan()->numeric(99);

        self::assertEquals('$myVar_eq_h_and_w_lt_99', (string)$expr1);

        $moduloAddExpr = $moduloExpr->add()->expression($addExpr);

        self::assertEquals('$myVar_mod_w_add_17_add_w', (string)$moduloAddExpr);

        $inExpr = PVar::tags()->in()->stringArray(['apple', 'orange', 'kiwi']);

        self::assertEquals('tags_in_!apple:orange:kiwi!', (string)$inExpr);
    }

    public function testExpressionInParameter()
    {
        $addExpr = UVar::uVar('someVal')->add()->width();

        self::assertEquals('$someVal_add_w', (string)$addExpr);

        $c = Crop::thumbnail($addExpr, 17);

        self::assertEquals('c_thumb,h_17,w_$someVal_add_w', (string)$c);
    }

    public function testRawExpression()
    {
        $expr = 'width = 0 && height != 0 || aspect_ratio < 0 && page_count > 0 and face_count <= 0 and width >= 0';

        $rawExpr = Expression::raw($expr);

        $expectedExpr = 'w_eq_0_and_h_ne_0_or_ar_lt_0_and_pc_gt_0_and_fc_lte_0_and_w_gte_0';

        self::assertEquals($expectedExpr, (string)$rawExpr);

        self::assertEquals($expectedExpr . '_gt_997', (string)$rawExpr->greaterThan()->numeric(997));
    }
}
