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

use Cloudinary\Transformation\Argument\Text\Stroke;
use Cloudinary\Transformation\Argument\Text\TextStyleTrait;

/**
 * Defines how to manipulate a text layer.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/image_transformations#adding_text_captions" target="_blank">
 * Adding text captions</a>
 *
 * @api
 */
class TextSource extends BaseSource implements ImageTransformationInterface
{
    use ImageTransformationTrait;

    use TextStyleTrait;
    use TextColorTrait;
    use BackgroundColorTrait {
        BackgroundColorTrait::backgroundColor insteadof ImageTransformationTrait;
    }
    use TextFitTrait;

    /**
     * TextLayer constructor.
     *
     * @param string $text
     * @param null   $style
     * @param null   $color
     * @param null   $backgroundColor
     */
    public function __construct($text = null, $style = null, $color = null, $backgroundColor = null)
    {
        parent::__construct();

        $this->text($text);
        $this->textStyle($style);
        $this->textColor($color);
        $this->backgroundColor($backgroundColor);
    }

    /**
     * Gets the transformation.
     *
     * @return ImageTransformation
     *
     * @internal
     */
    public function getTransformation()
    {
        if (! isset($this->transformation)) {
            $this->transformation = new ImageTransformation();
        }

        return $this->transformation;
    }

    /**
     * Gets the layer qualifier.
     *
     * @return TextSourceQualifier
     *
     * @internal
     */
    protected function getSourceQualifier()
    {
        if (! isset($this->qualifiers['source'])) {
            $this->qualifiers['source'] = new TextSourceQualifier();
        }

        return $this->qualifiers['source'];
    }

    /**
     * Sets the text.
     *
     * @param string $text The text.
     *
     * @return $this
     */
    public function text($text)
    {
        $this->getSourceQualifier()->text($text);

        return $this;
    }

    /**
     * Sets the text style.
     *
     * @param string|array|TextStyle $style The text style.
     *
     * @return $this
     */
    public function textStyle($style)
    {
        $this->getSourceQualifier()->textStyle($style);

        return $this;
    }

    /**
     * Adds a small amount of padding around the text overlay string.
     *
     * @return $this
     *
     * @see Flag::textNoTrim
     */
    public function noTrim()
    {
        return $this->addFlag(Flag::textNoTrim());
    }

    /**
     * Returns an error if the text overlay exceeds the image boundaries.
     *
     * @return $this
     *
     * @see Flag::textDisallowOverflow
     */
    public function disallowOverflow()
    {
        return $this->addFlag(Flag::textDisallowOverflow());
    }

    /**
     * Internal setter for text style property.
     *
     * @param string $styleName The style name.
     * @param string $value     The style.
     * @param bool   $named     Indicates whether the property is a named property.
     *
     * @return static
     *
     * @internal
     */
    protected function setStyleProperty($styleName, $value, $named = false)
    {
        $this->getSourceQualifier()->setStyleProperty($styleName, $value, $named);

        return $this;
    }

    /**
     * Sets whether to include an outline stroke.
     *
     * @param string|BorderQualifier $stroke The text outline stroke.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\Argument\Text\Stroke
     */
    public function stroke($stroke = Stroke::STROKE)
    {
        if ($stroke instanceof BorderQualifier) {
            $this->addQualifier($stroke);
            $stroke = Stroke::STROKE;
        }

        $this->getSourceQualifier()->setStyleProperty('stroke', $stroke);

        return $this;
    }
}
