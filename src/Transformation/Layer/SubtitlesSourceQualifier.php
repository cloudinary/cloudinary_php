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
use Cloudinary\Transformation\Argument\Text\TextStyleTrait;

/**
 * Class SubtitlesLayerQualifier
 */
class SubtitlesSourceQualifier extends BaseSourceQualifier
{
    /**
     * @var string $sourceType The type of the layer.
     */
    protected $sourceType = 'subtitles';

    /**
     * @var array $valueOrder The order of the values.
     */
    protected $valueOrder = ['text_style', 'subtitles_id'];

    use TextStyleTrait;

    /**
     * SubtitlesLayerQualifier constructor.
     *
     * @param string|SourceValue|mixed $subtitlesId The public ID of the subtitles asset.
     * @param TextStyle                $style       The text style of subtitles.
     */
    public function __construct($subtitlesId, $style = null)
    {
        parent::__construct();

        $this->subtitlesId($subtitlesId)->textStyle($style);
    }

    /**
     * Sets subtitles public ID.
     *
     * @param string|SourceValue|mixed $subtitlesId The public ID of the subtitles asset.
     *
     * @return $this
     */
    public function subtitlesId($subtitlesId)
    {
        $this->value->setSimpleValue('subtitles_id', ClassUtils::verifyInstance($subtitlesId, SourceValue::class));

        return $this;
    }

    /**
     * Sets the text style of the subtitles.
     *
     * @param array|TextStyle $style The style.
     *
     * @return $this
     */
    public function textStyle($style)
    {
        if (is_array($style)) {
            $style = TextStyle::fromParams($style);
        }

        $this->value->setValue($style);

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
        $this->getStyle()->setStyleProperty($styleName, $value, $named, $defaultValue);

        return $this;
    }
}
