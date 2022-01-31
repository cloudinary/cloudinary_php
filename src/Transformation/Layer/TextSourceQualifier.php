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

use Cloudinary\ClassUtils;
use Cloudinary\Transformation\Argument\Text\Text;
use Cloudinary\Transformation\Argument\Text\TextStyleTrait;

/**
 * Class TextSourceQualifier
 */
class TextSourceQualifier extends BaseSourceQualifier
{
    use TextStyleTrait;

    /**
     * @var string $sourceType The type of the layer.
     */
    protected $sourceType = 'text';

    /**
     * @var array $valueOrder The order of the values.
     */
    protected $valueOrder = ['text_style', 'source_value', 'text'];

    /**
     * TextSourceQualifier constructor.
     *
     * @param string|Text     $text  The text.
     * @param array|TextStyle $style The text style.
     */
    public function __construct($text = null, $style = null)
    {
        parent::__construct();

        $this->text($text)->textStyle($style);
    }

    /**
     * Sets the text.
     *
     * @param string|Text $text The text.
     *
     * @return $this
     */
    public function text($text)
    {
        $this->value->setValue(ClassUtils::verifyInstance($text, Text::class));

        return $this;
    }

    /**
     * Sets the text style.
     *
     * @param array|TextStyle|string $style The text style.
     *
     * @return $this
     */
    public function textStyle($style)
    {
        if (is_array($style)) {
            $style = TextStyle::fromParams($style);
        }

        $this->value->setValue(ClassUtils::forceInstance($style, TextStyle::class));

        return $this;
    }

    /**
     * Text style can be set to a public ID of the text asset that contains style.
     *
     * @param string|SourceValue $publicId The public ID of the text asset.
     *
     * @return $this
     */
    public function styleFromPublicId($publicId)
    {
        $this->value->setValue(ClassUtils::verifyInstance($publicId, SourceValue::class));

        return $this;
    }

    /**
     * Gets the text style.
     *
     * @return TextStyle
     */
    protected function getStyle()
    {
        if (!$this->value->getSimpleValue('text_style')) {
            $this->value->setValue(new TextStyle());
        }

        return $this->value->getSimpleValue('text_style');
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
    public function setStyleProperty($styleName, $value, $named = false)
    {
        return $this->getStyle()->setStyleProperty($styleName, $value, $named);
    }
}
