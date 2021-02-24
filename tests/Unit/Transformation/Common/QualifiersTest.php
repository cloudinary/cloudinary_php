<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Transformation\Common;

use Cloudinary\Transformation\Argument\Color;
use Cloudinary\Transformation\Argument\ColorValue;
use Cloudinary\Transformation\Argument\GenericArgument;
use Cloudinary\Transformation\Argument\GenericNamedArgument;
use Cloudinary\Transformation\Argument\Text\FontFamily;
use Cloudinary\Transformation\Argument\Text\Text;
use Cloudinary\Transformation\Qualifier;
use Cloudinary\Transformation\Qualifier\Dimensions\Height;
use Cloudinary\Transformation\TextStyle;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Class QualifiersTest
 */
final class QualifiersTest extends TestCase
{

    /**
     * Data provider for `testColorConstants()`
     *
     * @return array
     */
    public function colorConstantsDataProvider()
    {
        $reflectionClass = new ReflectionClass(Color::class);
        $colors          = ($reflectionClass->getConstants());
        $colors          = array_diff(
            $colors,
            [
                'ARG_NAME_VALUE_DELIMITER'  => Color::ARG_NAME_VALUE_DELIMITER,
                'ARG_INNER_VALUE_DELIMITER' => Color::ARG_INNER_VALUE_DELIMITER,
            ]
        );
        $colors = array_map(
            static function ($color) {
                return [$color];
            },
            $colors
        );

        return $colors;
    }

    /**
     * Tests color constants.
     *
     * @dataProvider colorConstantsDataProvider
     *
     * @param string $color
     */
    public function testColorConstants($color)
    {
        self::assertEquals(
            $color,
            (string)Color::{$color}()
        );
        self::assertEquals(
            $color,
            (string)ColorValue::{$color}()
        );
    }

    public function testColorByValue()
    {
        self::assertEquals(
            '#ff9900',
            (string)Color::rgb('#ff9900')
        );

        self::assertEquals(
            'rgb:ff11aa',
            (string)(new ColorValue('#000000'))->color(Qualifier::color('#ff11aa'))
        );

        self::assertEquals(
            'co_rgb:001122',
            (string)Qualifier::color('#ff11aa')->color('#001122')
        );
    }

    /**
     * Should create a generic argument.
     */
    public function testGenericArgument()
    {
        $name = 'name';
        $value = ['value1', 'value2'];

        $genericArgument = new GenericArgument($name, $value, '|');

        self::assertEquals(
            [
                'name' => $name,
                'value' => $value,
            ],
            $genericArgument->jsonSerialize()
        );

        self::assertEquals(
            'name_value1|value2',
            (string)$genericArgument
        );
    }

    /**
     * Should create a generic named argument.
     */
    public function testGenericNamedArgument()
    {
        $name = 'name';
        $value = ['value1', 'value2'];

        $genericArgument = new GenericNamedArgument($name, $value, '!', '|');

        self::assertEquals(
            [
                'name' => $name,
                'value' => $value,
            ],
            $genericArgument->jsonSerialize()
        );

        self::assertEquals(
            'name!value1|value2',
            (string)$genericArgument
        );
    }

    /**
     * Should set a named value.
     */
    public function testSetNamedValue()
    {
        self::assertEquals(
            'text_style_Parisienne_35',
            (string)(new Text())->setNamedValue(new TextStyle(FontFamily::PARISIENNE, 35))
        );
        self::assertEquals(
            'h_100_text_style_Parisienne',
            (string)(new Text())->setNamedValues(new TextStyle(FontFamily::PARISIENNE), Height::fromValue(100))
        );
    }
}
