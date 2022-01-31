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

/**
 * Class MediaOverlay
 *
 * @package Cloudinary\Transformation
 */
class MediaOverlay extends ImageOverlay
{
    /**
     * @var Timeline $timeline The timeline position of the overlay.
     */
    protected $timeline;

    /**
     * Sets the timeline position of the overlay.
     *
     * @param Timeline|null $timeline The timeline position of the overlay.
     *
     * @return MediaOverlay
     */
    public function timeline(Timeline $timeline = null)
    {
        $this->timeline = $timeline;

        return $this;
    }

    /**
     * @return array
     */
    protected function getSubActionQualifiers()
    {
        $subActionQualifiers = parent::getSubActionQualifiers();

        $subActionQualifiers['additional'] = ArrayUtils::mergeNonEmpty(
            $subActionQualifiers['additional'],
            $this->timeline? $this->timeline->getStringQualifiers(): []
        );

        return $subActionQualifiers;
    }
}
