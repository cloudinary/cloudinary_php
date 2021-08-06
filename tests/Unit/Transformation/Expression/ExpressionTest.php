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
use Cloudinary\Transformation\Expression\ArithmeticOperator;
use Cloudinary\Transformation\Expression\Expression;
use Cloudinary\Transformation\Expression\ExpressionComponent;
use Cloudinary\Transformation\Expression\ExpressionUtils;
use Cloudinary\Transformation\Expression\ExpressionOperator;
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
        $aeJson = $ae->jsonSerialize();

        self::assertEquals('iar_add_17', (string)$ae);
        self::assertEquals(PVar::INITIAL_ASPECT_RATIO, $aeJson['left_operand']);
        self::assertInstanceOf(ArithmeticOperator::class, $aeJson['operator']);
        self::assertInstanceOf(UVal::class, $aeJson['right_operand']);
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
        $leJson = $le->jsonSerialize();

        self::assertInstanceOf(ExpressionComponent::class, $leJson['left_operand']);
        self::assertInstanceOf(LogicalOperator::class, $leJson['operator']);
        self::assertInstanceOf(Expression::class, $leJson['right_operand']);
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

        $addExpr = UVal::int(1)->add()->initialWidth()->notEqual()->initialHeight()->multiply()->numeric(0.1);

        self::assertEquals('1_add_iw_ne_ih_mul_0.1', (string)$addExpr);

        $addExpr = UVal::int(-1)->add()->initialAspectRatio()->add()->trimmedAspectRatio()->add()->pageCount()
                       ->add()->currentPage();

        self::assertEquals('-1_add_iar_add_tar_add_pc_add_cp', (string)$addExpr);

        $addExpr = UVal::int(5)->add()->illustrationScore()->add()->pageX()->add()->pageY()->add()->context()
                       ->add()->aspectRatio()->add()->tags();

        self::assertEquals('5_add_ils_add_px_add_py_add_ctx_add_ar_add_tags', (string)$addExpr);
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

        $expr2 = UVar::uVar('myVar')->subtract()->string('string')->or_()->width()->lessThan()->numeric(99);

        self::assertEquals('$myVar_sub_!string!_or_w_lt_99', (string)$expr2);

        $expr3 = UVar::uVar('myVar')->greaterThanOrEqual()->numeric(9)->add()->assetReference('public_id');

        self::assertEquals('$myVar_gte_9_add_ref:!public_id!', (string)$expr3);

        $expr4 = UVal::uVal('myValue')->equal()->string('d')->notEqual()->string('s');

        self::assertEquals('myValue_eq_!d!_ne_!s!', (string)$expr4);

        $moduloAddExpr = $moduloExpr->add()->expression($addExpr);

        self::assertEquals('$myVar_mod_w_add_17_add_w', (string)$moduloAddExpr);

        $inExpr = PVar::tags()->in()->stringArray(['apple', 'orange', 'kiwi'])->notIn()->stringArray(['chery']);

        self::assertEquals('tags_in_!apple:orange:kiwi!_nin_!chery!', (string)$inExpr);

        $expressionOperator = new ExpressionOperator(new ExpressionComponent(PVar::initialAspectRatio()));

        self::assertEquals('iar_$vName', (string)$expressionOperator->uVar('vName'));
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

        $rawExpr = Expression::expression($expr);

        $expectedExpr = 'w_eq_0_and_h_ne_0_or_ar_lt_0_and_pc_gt_0_and_fc_lte_0_and_w_gte_0';

        self::assertEquals($expectedExpr, (string)$rawExpr);

        self::assertEquals($expectedExpr . '_gt_997', (string)$rawExpr->greaterThan()->numeric(997));
    }

    /**
     * Check expression normalization
     *
     * @dataProvider testNormalizationDataProvider
     *
     * @param mixed       $input          Value to normalize
     * @param null|string $expectedOutput Expected normalized output
     */
    public function testExpressionNormalization($input, $expectedOutput)
    {
        $actual = ExpressionUtils::normalize($input);
        self::assertEquals($expectedOutput, $actual);
    }

    /**
     * Data provider for testExpressionNormalization
     *
     * @return array[]
     */
    public static function testNormalizationDataProvider()
    {
        return [
            'null is not affected' => [null, null],
            'number replaced with a string value' => [10, '10'],
            'empty string is not affected' => ['', ''],
            'single space is replaced with a single underscore' => [' ', '_'],
            'blank string is replaced with a single underscore' => ['   ', '_'],
            'underscore is not affected' => ['_', '_'],
            'sequence of underscores and spaces is replaced with a single underscore' => [' _ __  _', '_'],
            'arbitrary text is not affected' => ['foobar', 'foobar'],
            'double ampersand replaced with and operator' => ['foo && bar', 'foo_and_bar'],
            'double ampersand with no space at the end is not affected' => ['foo&&bar', 'foo&&bar'],
            'width recognized as variable and replaced with w' => ['width', 'w'],
            'initial aspect ratio recognized as variable and replaced with iar' => ['initial_aspect_ratio', 'iar'],
            '$width recognized as user variable and not affected' => ['$width', '$width'],
            '$initial_aspect_ratio recognized as user variable followed by aspect_ratio variable' => [
                '$initial_aspect_ratio',
                '$initial_ar',
            ],
            '$mywidth recognized as user variable and not affected' => ['$mywidth', '$mywidth'],
            '$widthwidth recognized as user variable and not affected' => ['$widthwidth', '$widthwidth'],
            '$_width recognized as user variable and not affected' => ['$_width', '$_width'],
            '$__width recognized as user variable and not affected' => ['$__width', '$_width'],
            '$$width recognized as user variable and not affected' => ['$$width', '$$width'],
            '$height recognized as user variable and not affected' => ['$height_100', '$height_100'],
            '$heightt_100 recognized as user variable and not affected' => ['$heightt_100', '$heightt_100'],
            '$$height_100 recognized as user variable and not affected' => ['$$height_100', '$$height_100'],
            '$heightmy_100 recognized as user variable and not affected' => ['$heightmy_100', '$heightmy_100'],
            '$myheight_100 recognized as user variable and not affected' => ['$myheight_100', '$myheight_100'],
            '$heightheight_100 recognized as user variable and not affected' => [
                '$heightheight_100',
                '$heightheight_100',
            ],
            '$theheight_100 recognized as user variable and not affected' => ['$theheight_100', '$theheight_100'],
            '$__height_100 recognized as user variable and not affected' => ['$__height_100', '$_height_100']
        ];
    }
}
