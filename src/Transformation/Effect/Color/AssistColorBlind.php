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
 * Class AssistColorBlind
 *
 * @see ImageColorEffectTrait::assistColorBlind()
 */
class AssistColorBlind extends LimitedEffectQualifier
{
    const X_RAY = 'xray';

    /**
     * AssistColorBlind constructor.
     *
     * @param null $strength
     */
    public function __construct($strength = null)
    {
        parent::__construct(ColorEffect::ASSIST_COLOR_BLIND, EffectRange::PERCENT);

        $this->setAssistType($strength);
    }

    /**
     * Applies stripes to the image.
     *
     * @param int $strength The strength of the stripes.  (Range: 1 to 100, Server default: 10)
     *
     * @return AssistColorBlind
     */
    public function stripesStrength($strength)
    {
        return $this->setAssistType($strength);
    }

    /**
     * Replaces problematic colors with colors that look the same to people with and
     * without common colorblind conditions.
     *
     * @return AssistColorBlind
     */
    public function xRay()
    {
        return $this->setAssistType(self::X_RAY);
    }

    /**
     * Sets the type of the assistance.
     *
     * @param string $type
     *
     * @return AssistColorBlind
     *
     * @internal
     */
    protected function setAssistType($type)
    {
        $this->value->setSimpleValue('assist_type', $type);

        return $this;
    }
}
