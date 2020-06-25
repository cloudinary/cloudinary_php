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
use Cloudinary\Transformation\Parameter\BaseParameter;

/**
 * Determines the video codec to use.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/video_manipulation_and_delivery#video_codec_settings" target="_blank">
 * Video codec settings</a>
 *
 * @api
 */
class VideoCodec extends BaseParameter
{
    use VideoCodecTrait;

    const AUTO    = 'auto';
    const VP9     = 'vp9';
    const VP8     = 'vp8';
    const PRO_RES = 'prores'; // Apple ProRes 422 HQ
    const H264    = 'h264';
    const H265    = 'h265';
    const THEORA  = 'theora';

    /**
     * @var array $valueOrder The order of the values.
     */
    protected $valueOrder = ['codec', 'profile', 'level'];

    /**
     * VideoCodec constructor.
     *
     * @param string $codec The video codec.
     */
    public function __construct($codec)
    {
        parent::__construct();

        $this->codec($codec);
    }

    /**
     * Sets the codec.
     *
     * @param string $codec The codec name.
     *
     * @return $this
     */
    public function codec($codec)
    {
        $this->value->setSimpleValue('codec', $codec);

        return $this;
    }

    /**
     * Sets codec profile.
     *
     * @param string $profile The profile. Use the constants defined in the VideoCodecProfile class.
     *
     * @return $this
     *
     * @see VideoCodecProfile
     */
    public function profile($profile)
    {
        $this->value->setSimpleValue('profile', $profile);

        return $this;
    }

    /**
     * Sets codec level.
     *
     * @param string $level The codec level. Use the constants defined in the VideoCodecLevel class.
     *
     * @return $this
     *
     * @see VideoCodecLevel
     */
    public function level($level)
    {
        $this->value->setSimpleValue('level', $level);

        return $this;
    }

    /**
     * Creates a new VideoCodec instance from an array of parameters.
     *
     * @param array $params The video codec parameters.
     *
     * @return VideoCodec
     */
    public static function fromParams($params)
    {
        if (is_array($params)) {
            $codec   = ArrayUtils::get($params, 'codec');
            $profile = ArrayUtils::get($params, 'profile');
            $level   = ArrayUtils::get($params, 'level');
        } else {
            $codec   = $params;
            $profile = $level = null;
        }

        return (new static($codec))->profile($profile)->level($level);
    }
}
