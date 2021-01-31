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
 * Defines how to manipulate a video layer.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/video_manipulation_and_delivery#adding_video_overlays" target="_blank">
 * Video overlays</a>
 *
 * @api
 */
class Transition extends VideoSource
{
    /**
     * VideoTransitionSource constructor.
     *
     * @param $source
     */
    public function __construct($source)
    {
        parent::__construct(ClassUtils::verifyInstance($source, VideoSourceQualifier::class));

        $this->addQualifier(Effect::transition());
    }

    /**
     * Named constructor.
     *
     * @param $source
     *
     * @return Transition
     */
    public static function videoSource($source)
    {
        return new self($source);
    }
}
