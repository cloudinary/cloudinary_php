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

use Cloudinary\Transformation\Argument\Text\FontAntialiasing;
use Cloudinary\Transformation\Argument\Text\FontFamily;
use Cloudinary\Transformation\Argument\Text\FontWeight;
use Cloudinary\Transformation\Argument\Text\Stroke;
use Cloudinary\Transformation\Argument\Text\TextAlignment;
use Cloudinary\Transformation\Argument\Text\TextDecoration;
use Cloudinary\Transformation\Argument\Text\TextStyle;
use PHPUnit\Framework\TestCase;

/**
 * Class TextStyleTest
 */
final class TextStyleTest extends TestCase
{
    public function testTextStyle()
    {
        $this->assertEquals(
            'Arial_14',
            (string)new TextStyle(FontFamily::ARIAL, 14)
        );

        $this->assertEquals(
            'Parisienne_17_ultrabold_strikethrough_justify_stroke_antialias_subpixel_letter_spacing_17',
            (string)(new TextStyle(FontFamily::PARISIENNE, 17))
                ->fontAntialiasing(FontAntialiasing::SUBPIXEL)
                ->letterSpacing(17)
                ->textDecoration(TextDecoration::STRIKETHROUGH)
                ->fontWeight(FontWeight::ULTRABOLD)
                ->textAlignment(TextAlignment::JUSTIFY)
                ->stroke(Stroke::STROKE)
        );
    }
}
