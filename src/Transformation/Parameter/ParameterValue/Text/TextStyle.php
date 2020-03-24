<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Argument\Text;

use Cloudinary\ArrayUtils;
use Cloudinary\Transformation\ParameterMultiValue;

/**
 * Class TextStyle
 */
class TextStyle extends ParameterMultiValue
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
     * Creates a new instance from an array of parameters.
     *
     * @param array $params The text style parameters.
     *
     * @return TextStyle
     */
    public static function fromParams($params)
    {
        $style = new self(ArrayUtils::get($params, 'font_family'), ArrayUtils::get($params, 'font_size'));
        $style->importParams($params);

        return $style;
    }

    /**
     * Imports text style parameters to the current instance.
     *
     * @param array $params The text style parameters.
     *
     * @internal
     */
    public function importParams($params)
    {
        $this->fontWeight(ArrayUtils::get($params, 'font_weight'));
        $this->fontStyle(ArrayUtils::get($params, 'font_style'));
        $this->textDecoration(ArrayUtils::get($params, 'text_decoration'));
        $this->textAlignment(ArrayUtils::get($params, 'text_align'));
        $this->stroke(ArrayUtils::get($params, 'stroke'));
        $this->letterSpacing(ArrayUtils::get($params, 'letter_spacing'));
        $this->lineSpacing(ArrayUtils::get($params, 'line_spacing'));
        $this->fontAntialiasing(ArrayUtils::get($params, 'font_antialiasing'));
        $this->fontHinting(ArrayUtils::get($params, 'font_hinting'));
    }
}
