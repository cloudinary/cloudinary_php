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

use Cloudinary\Asset\Image;
use Cloudinary\Test\Unit\Asset\AssetTestCase;
use Cloudinary\Transformation\Argument\Text\FontAntialias;
use Cloudinary\Transformation\Argument\Text\FontFamily;
use Cloudinary\Transformation\Argument\Text\FontWeight;
use Cloudinary\Transformation\Argument\Text\TextAlignment;
use Cloudinary\Transformation\Argument\Text\TextDecoration;
use Cloudinary\Transformation\Expression\Expression;
use Cloudinary\Transformation\ImageSource;
use Cloudinary\Transformation\TextStyle;
use Cloudinary\Transformation\Transformation;

/**
 * Class TextStyleTest
 */
final class TextStyleTest extends AssetTestCase
{
    public function testTextStyle()
    {
        self::assertEquals(
            'Arial_14',
            (string)new TextStyle(FontFamily::ARIAL, 14)
        );

        self::assertEquals(
            'Parisienne_17_ultrabold_strikethrough_justify_stroke_antialias_subpixel_letter_spacing_17',
            (string)(new TextStyle(FontFamily::PARISIENNE, 17))
                ->fontAntialias(FontAntialias::SUBPIXEL)
                ->letterSpacing(17)
                ->textDecoration(TextDecoration::STRIKETHROUGH)
                ->fontWeight(FontWeight::ULTRABOLD)
                ->textAlignment(TextAlignment::JUSTIFY)
                ->stroke()
        );
    }

    public function testTextLayerStyleIdentifierVariables()
    {
        $image = new Image(self::IMAGE_NAME);
        $image->addTransformation(
            (new Transformation())
                ->addVariable('$style', '!Arial_12!')
                ->overlay(
                    ImageSource::text('hello-world')->textStyle('$style')
                )
        );

        self::assertImageUrl(
            '$style_!Arial_12!/l_text:$style:hello-world/fl_layer_apply/' . self::IMAGE_NAME,
            $image->toUrl()
        );

        $image = new Image(self::IMAGE_NAME);
        $image->addTransformation(
            (new Transformation())
                ->addVariable('$style', '!Arial_12!')
                ->overlay(
                    ImageSource::text('hello-world')->textStyle(new Expression('$style'))
                )
        );

        self::assertImageUrl(
            '$style_!Arial_12!/l_text:$style:hello-world/fl_layer_apply/' . self::IMAGE_NAME,
            $image->toUrl()
        );
    }
}
