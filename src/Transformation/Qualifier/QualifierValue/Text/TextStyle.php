<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation;

use Cloudinary\ArrayUtils;
use Cloudinary\Transformation\Argument\Text\TextStyleTrait;

/**
 * Class TextStyle
 */
class TextStyle extends QualifierMultiValue
{
    const VALUE_DELIMITER = '_';

    const DEFAULT_FONT_WEIGHT     = 'normal';
    const DEFAULT_FONT_STYLE      = 'normal';
    const DEFAULT_TEXT_DECORATION = 'none';
    const DEFAULT_TEXT_ALIGNMENT  = null;
    const DEFAULT_STROKE          = 'none';

    use TextStyleTrait;

    /**
     * @var array $argumentOrder The order of the arguments.
     */
    protected $argumentOrder = [
        'font_family',
        'font_size',
        'font_weight',
        'font_style',
        'text_decoration',
        'text_alignment',
        'stroke',
    ];

    /**
     * TextStyle constructor
     *
     * @param null $fontFamily
     * @param null $fontSize
     */
    public function __construct($fontFamily = null, $fontSize = null)
    {
        parent::__construct();

        $this->fontFamily($fontFamily);
        $this->fontSize($fontSize);
    }

    /**
     * Creates a new instance from an array of qualifiers.
     *
     * @param array $qualifiers The text style qualifiers.
     *
     * @return TextStyle
     */
    public static function fromParams($qualifiers)
    {
        $style = new self(ArrayUtils::get($qualifiers, 'font_family'), ArrayUtils::get($qualifiers, 'font_size'));
        $style->importQualifiers($qualifiers);

        return $style;
    }

    /**
     * Imports text style qualifiers to the current instance.
     *
     * @param array $qualifiers The text style qualifiers.
     *
     * @internal
     */
    public function importQualifiers($qualifiers)
    {
        $this->fontWeight(ArrayUtils::get($qualifiers, 'font_weight'));
        $this->fontStyle(ArrayUtils::get($qualifiers, 'font_style'));
        $this->textDecoration(ArrayUtils::get($qualifiers, 'text_decoration'));
        $this->textAlignment(ArrayUtils::get($qualifiers, 'text_align'));
        $this->stroke(ArrayUtils::get($qualifiers, 'stroke'));
        $this->letterSpacing(ArrayUtils::get($qualifiers, 'letter_spacing'));
        $this->lineSpacing(ArrayUtils::get($qualifiers, 'line_spacing'));
        $this->fontAntialias(ArrayUtils::get($qualifiers, 'font_antialiasing'));
        $this->fontHinting(ArrayUtils::get($qualifiers, 'font_hinting'));
    }
}
