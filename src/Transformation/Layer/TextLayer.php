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

use Cloudinary\Transformation\Argument\Text\TextStyle;
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
class TextLayer extends BaseLayer implements ImageTransformationInterface
{
    use ImageTransformationTrait;

    use TextStyleTrait;
    use ColorTrait;
    use BackgroundTrait {
        BackgroundTrait::background insteadof ImageTransformationTrait;
    }

    /**
     * TextLayer constructor.
     *
     * @param string $text
     * @param null   $style
     * @param null   $color
     * @param null   $background
     */
    public function __construct($text = null, $style = null, $color = null, $background = null)
    {
        parent::__construct();

        $this->text($text);
        $this->style($style);
        $this->color($color);
        $this->background($background);
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
     * Gets the layer parameter.
     *
     * @return TextLayerParam
     *
     * @internal
     */
    protected function getLayerParam()
    {
        if (! isset($this->parameters['layer'])) {
            $this->parameters['layer'] = new TextLayerParam();
        }

        return $this->parameters['layer'];
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
        $this->getLayerParam()->text($text);

        return $this;
    }

    /**
     * Sets the text style.
     *
     * @param array|TextStyle $style The text style.
     *
     * @return $this
     */
    public function style($style)
    {
        $this->getLayerParam()->style($style);

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
        $this->getLayerParam()->setStyleProperty($styleName, $value, $named);

        return $this;
    }
}
