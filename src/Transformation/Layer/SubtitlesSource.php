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
 * Defines how to manipulate a subtitle layer.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/video_manipulation_and_delivery#adding_subtitles" target="_blank">
 * Adding subtitles</a>
 *
 * @api
 */
class SubtitlesSource extends AssetBasedSource
{
    use TextColorTrait;
    use BackgroundColorTrait;
    use TextStyleTrait;

    /**
     * SubtitlesLayer constructor.
     *
     * @param $subtitlesId
     */
    public function __construct($subtitlesId)
    {
        parent::__construct(ClassUtils::verifyInstance($subtitlesId, SubtitlesSourceQualifier::class));
    }

    /**
     * Gets the transformation.
     *
     * @return VideoTransformation
     */
    public function getTransformation()
    {
        if (! isset($this->transformation)) {
            $this->transformation = new VideoTransformation();
        }

        return $this->transformation;
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
        $this->getSourceQualifier()->textStyle($style);

        return $this;
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
     * Gets the layer qualifier.
     *
     * @return SubtitlesSourceQualifier
     *
     * @internal
     */
    protected function getSourceQualifier()
    {
        if (! isset($this->qualifiers['source'])) {
            $this->qualifiers['source'] = new SubtitlesSourceQualifier(null);
        }

        return $this->qualifiers['source'];
    }
}
