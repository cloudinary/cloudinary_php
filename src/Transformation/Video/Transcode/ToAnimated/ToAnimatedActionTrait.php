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

/**
 * Trait ToAnimatedActionTrait
 *
 * @api
 */
trait ToAnimatedActionTrait
{
    /**
     * Sets the total number of frames to sample from the original video.
     *
     * Relevant when converting videos to animated GIF or WebP format. If not specified, the resulting GIF or WebP
     * samples the whole video (up to 400 frames, at up to 10 frames per second). By default the duration of the
     * animated image is the same as the duration of the video, no matter how many frames are sampled from the original
     * video (use the delay qualifier to adjust the amount of time between frames).
     *
     * @param int|string $value Integer - The total number of frames to sample from the original video. The frames are
     *                          spread out over the length of the video, e.g. 20 takes one frame every 5%.
     *                          String - The number of seconds between each frame to sample from the original video.
     *                          e.g. 2.3s takes one frame every 2.3 seconds.
     *
     * @return static
     */
    public function sampling($value)
    {
        return $this->addQualifier(ClassUtils::verifyInstance($value, VideoSampling::class));
    }

    /**
     * Sets the delay between frames of an animated image in milliseconds.
     *
     * @param Delay|int $delay
     *
     * @return static
     *
     * @deprecated use Animated::edit()->delay($delay) instead.
     *
     * @see AnimatedEdit
     */
    public function delay($delay)
    {
        return $this->addQualifier(ClassUtils::verifyInstance($delay, Delay::class));
    }

    /**
     * Sets the animated image format.
     *
     * @param AnimatedFormat|string $format The format.
     *
     * @return static
     */
    public function format($format)
    {
        return $this->importAction(ClassUtils::verifyInstance($format, AnimatedFormat::class));
    }
}
