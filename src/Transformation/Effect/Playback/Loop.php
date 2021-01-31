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
 * Class Loop
 */
class Loop extends EffectAction
{
    /**
     * Loop constructor.
     *
     * @param int   $additionalIterations The additional number of times to play the video or animated GIF.
     * @param mixed ...$args              Additional arguments.
     */
    public function __construct($additionalIterations, ...$args)
    {
        parent::__construct(new ValueEffectQualifier(PlaybackEffect::LOOP, $additionalIterations, ...$args));
    }

    /**
     * Setter of additional iterations.
     *
     * @param int $additionalIterations The additional number of times to play the video or animated GIF.
     *
     * @return $this
     */
    public function additionalIterations($additionalIterations)
    {
        $this->qualifiers[EffectQualifier::getName()]->setEffectValue($additionalIterations);

        return $this;
    }
}
