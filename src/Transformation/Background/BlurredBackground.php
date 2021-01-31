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

/**
 * Applies a blur to the background of a video when resizing with padding.
 *
 * **Learn more**:
 * <a href="https://cloudinary.com/documentation/video_manipulation_and_delivery#pad_with_blurred_video_background"
 * target="_blank">Apply blurred video background</a>
 *
 * @api
 */
class BlurredBackground extends Background
{
    /**
     * @var string $name The name.
     */
    protected static $name = 'background';

    /**
     * @var array $valueOrder The order of the values.
     */
    protected $valueOrder = [0, 'intensity', 'brightness']; // FIXME: first item should be named!

    /**
     * BlurredBackground constructor.
     *
     * @param int $intensity  The intensity of the blur.
     * @param int $brightness The brightness of the background.
     */
    public function __construct($intensity = null, $brightness = null)
    {
        parent::__construct('blurred');

        $this->intensity($intensity);
        $this->brightness($brightness);
    }


    /**
     * Sets the intensity of the blur.
     *
     * @param int $intensity The intensity of the blur.
     *
     * @return $this
     */
    public function intensity($intensity)
    {
        $this->value->setSimpleValue('intensity', $intensity);

        return $this;
    }

    /**
     * Sets the brightness of the background.
     *
     * @param int $brightness The brightness of the background.
     *
     * @return $this
     */
    public function brightness($brightness)
    {
        $this->value->setSimpleValue('brightness', $brightness);

        return $this;
    }
}
