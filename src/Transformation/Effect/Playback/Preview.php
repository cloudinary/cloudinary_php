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
use Cloudinary\Transformation\Argument\GenericNamedArgument;
use Cloudinary\Transformation\Argument\Range\PreviewDuration;

/**
 * Class Preview
 */
class Preview extends EffectAction
{
    /**
     * Preview constructor.
     *
     * @param null $duration
     * @param null $maximumSegments
     * @param null $minimumSegmentDuration
     */
    public function __construct($duration = null, $maximumSegments = null, $minimumSegmentDuration = null)
    {
        parent::__construct(PlaybackEffect::PREVIEW);

        $this->duration($duration);
        $this->maximumSegments($maximumSegments);
        $this->minimumSegmentDuration($minimumSegmentDuration);
    }

    /**
     * @param $duration
     *
     * @return Preview
     */
    public function duration($duration)
    {
        $this->getMainQualifier()->add(ClassUtils::verifyInstance($duration, PreviewDuration::class));

        return $this;
    }

    /**
     * @param $maximumSegments
     *
     * @return Preview
     */
    public function maximumSegments($maximumSegments)
    {
        if ($maximumSegments) {
            $this->getMainQualifier()->add(new GenericNamedArgument('max_seg', $maximumSegments));
        }

        return $this;
    }

    /**
     * @param $minimumSegmentDuration
     *
     * @return Preview
     */
    public function minimumSegmentDuration($minimumSegmentDuration)
    {
        if ($minimumSegmentDuration) {
            $this->getMainQualifier()->add(new GenericNamedArgument('min_seg_dur', $minimumSegmentDuration));
        }

        return $this;
    }
}
