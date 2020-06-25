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
use Cloudinary\Transformation\Argument\Text\TextStyle;
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
class SubtitlesLayer extends AssetBasedLayer
{
    use ColorTrait;
    use BackgroundTrait;
    use TextStyleTrait;

    /**
     * SubtitlesLayer constructor.
     *
     * @param $subtitlesId
     */
    public function __construct($subtitlesId)
    {
        parent::__construct(ClassUtils::verifyInstance($subtitlesId, SubtitlesLayerParam::class));
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
    public function style($style)
    {
        $this->getLayerParam()->style($style);

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
        $this->getLayerParam()->setStyleProperty($styleName, $value, $named);

        return $this;
    }

    /**
     * Gets the layer parameter.
     *
     * @return SubtitlesLayerParam
     *
     * @internal
     */
    protected function getLayerParam()
    {
        if (! isset($this->parameters['layer'])) {
            $this->parameters['layer'] = new SubtitlesLayerParam(null);
        }

        return $this->parameters['layer'];
    }
}
