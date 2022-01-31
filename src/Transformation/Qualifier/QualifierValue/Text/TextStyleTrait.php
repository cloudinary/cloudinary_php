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

use Cloudinary\Transformation\TextStyle;

/**
 * Trait TextStyleTrait
 */
trait TextStyleTrait
{
    /**
     * Sets the font family of the text.
     *
     * @param string $fontFamily The font family. Use the constants defined in the FontFamily class.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\Argument\Text\FontFamily
     */
    public function fontFamily($fontFamily)
    {
        return $this->setStyleProperty('font_family', $fontFamily);
    }

    /**
     * Sets the font size of the text.
     *
     * @param int|float $fontSize The font size.
     *
     * @return static
     */
    public function fontSize($fontSize)
    {
        return $this->setStyleProperty('font_size', $fontSize);
    }

    /**
     * Sets the font weight of the text.
     *
     * @param mixed $fontWeight The font weight. Use the constants defined in the FontWeight class.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\Argument\Text\FontWeight
     */
    public function fontWeight($fontWeight)
    {
        return $this->setStyleProperty('font_weight', $fontWeight, false, TextStyle::DEFAULT_FONT_WEIGHT);
    }

    /**
     * Sets the font style of the text.
     *
     * @param mixed $fontStyle The font style.  Use the constants defined in the FontStyle class.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\Argument\Text\FontStyle
     */
    public function fontStyle($fontStyle)
    {
        return $this->setStyleProperty('font_style', $fontStyle, false, TextStyle::DEFAULT_FONT_STYLE);
    }

    /**
     * Sets the text decoration.
     *
     * @param mixed $textDecoration The text decoration.  Use the constants defined in the TextDecoration class.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\Argument\Text\TextDecoration
     */
    public function textDecoration($textDecoration)
    {
        return $this->setStyleProperty('text_decoration', $textDecoration, false, TextStyle::DEFAULT_TEXT_DECORATION);
    }

    /**
     * Sets the alignment of the text.
     *
     * @param mixed $textAlignment The alignment of the text. Use the constants defined in the TextAlignment class.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\Argument\Text\TextAlignment
     */
    public function textAlignment($textAlignment)
    {
        return $this->setStyleProperty('text_alignment', $textAlignment, false, TextStyle::DEFAULT_TEXT_ALIGNMENT);
    }

    /**
     * Sets whether to include an outline stroke.
     *
     * @param mixed $stroke The stroke determiner. Use the constants defined in the Stroke class.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\Argument\Text\Stroke
     */
    public function stroke($stroke = Stroke::STROKE)
    {
        return $this->setStyleProperty('stroke', $stroke, false, TextStyle::DEFAULT_STROKE);
    }

    /**
     * Sets the spacing between the letters.
     *
     * @param mixed $letterSpacing The spacing between the letters in pixels.  Can be positive or negative.
     *
     * @return static
     */
    public function letterSpacing($letterSpacing)
    {
        return $this->setStyleProperty('letter_spacing', $letterSpacing, true);
    }

    /**
     * Sets the spacing between the lines.
     *
     * @param mixed $lineSpacing The spacing between multiple lines in pixels.
     *
     * @return static
     */
    public function lineSpacing($lineSpacing)
    {
        return $this->setStyleProperty('line_spacing', $lineSpacing, true);
    }

    /**
     * Sets the font antialiasing method.
     *
     * @param mixed $fontAntialias    The font antialiasing method.  Use the constants defined in the FontAntialias
     *                                class.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\Argument\Text\FontAntialias
     */
    public function fontAntialias($fontAntialias)
    {
        return $this->setStyleProperty('antialias', $fontAntialias, true);
    }

    /**
     * Sets the type of font hinting.
     *
     * @param mixed $fontHinting The type of font hinting. Use the constants defined in the FontHinting class.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\Argument\Text\FontHinting
     */
    public function fontHinting($fontHinting)
    {
        return $this->setStyleProperty('hinting', $fontHinting, true);
    }

    /**
     * Internal setter for text style property.
     *
     * @param string      $styleName    The style name.
     * @param string      $value        The style.
     * @param bool        $named        Indicates whether the property is a named property.
     * @param null|string $defaultValue The default value of the property. Used for omitting values that are default.
     *
     * @return static
     *
     * @internal
     */
    public function setStyleProperty($styleName, $value, $named = false, $defaultValue = null)
    {
        if ($value === $defaultValue) {
            return $this;
        }

        if ($named) {
            return $this->setSimpleNamedValue($styleName, $value);
        }

        return $this->setSimpleValue($styleName, $value);
    }
}
