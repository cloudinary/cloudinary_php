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

use Cloudinary\Test\Unit\UnitTestCase;
use Cloudinary\Transformation\Argument\Color;
use Cloudinary\Transformation\Argument\GradientDirection;
use Cloudinary\Transformation\Background;
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\Transformation;

/**
 * Class BackgroundTest
 */
final class BackgroundTest extends UnitTestCase
{
    public function testBackgroundColor()
    {
        self::assertStrEquals(
            'b_green',
            (new Transformation())->backgroundColor('green')
        );

        self::assertStrEquals(
            'b_green',
            (new Transformation())->backgroundColor(Color::GREEN)
        );

        self::assertStrEquals(
            'b_rgb:fff',
            (new Transformation())->backgroundColor(Color::rgb('fff'))
        );

        self::assertStrEquals(
            'b_rgb:fff',
            (new Transformation())->backgroundColor(Color::rgb('#fff'))
        );

        self::assertStrEquals(
            'b_rgb:fff',
            (new Transformation())->backgroundColor('#fff')
        );
    }

    public function testBlurredBackground()
    {
        self::assertStrEquals(
            'b_blurred:400:15,c_pad,h_500,w_500',
            (new Transformation())->resize(
                Resize::pad(500, 500)->background(Background::blurred()->intensity(400)->brightness(15))
            )
        );
    }

    public function testBackgroundAuto()
    {
        $pad = Resize::pad(500, 500);
        $padStr = 'c_pad,h_500,w_500';

        self::assertStrEquals(
            "b_auto,$padStr",
            Resize::pad(500, 500, Background::auto())
        );

        self::assertStrEquals(
            "b_auto,$padStr",
            $pad->background(Background::auto())
        );

        self::assertStrEquals(
            "b_auto:border,$padStr",
            $pad->background(Background::border())
        );

        self::assertStrEquals(
            "b_auto:border_gradient,$padStr",
            $pad->background(Background::borderGradient())
        );

        self::assertStrEquals(
            "b_auto:border_gradient_contrast,$padStr",
            $pad->background(Background::borderGradient()->contrast())
        );

        self::assertStrEquals(
            "b_auto:border_contrast:palette_red_green_blue,$padStr",
            $pad->background(
                Background::border()->contrast()->palette(Color::RED, Color::GREEN, Color::BLUE)
            )
        );

        self::assertStrEquals(
            "b_auto:predominant,$padStr",
            $pad->background(Background::predominant())
        );

        self::assertStrEquals(
            "b_auto:predominant_gradient_contrast:2:diagonal_asc:palette_red_blue,$padStr",
            $pad->background(
                Background::predominantGradient()
                          ->gradientColors(2)
                          ->gradientDirection(GradientDirection::DIAGONAL_ASC)
                          ->contrast()
                          ->palette(Color::RED, Color::BLUE)
            )
        );
    }
}
