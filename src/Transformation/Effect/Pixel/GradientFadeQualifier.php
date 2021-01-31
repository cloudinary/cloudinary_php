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
 * Class GradientFadeQualifier
 */
class GradientFadeQualifier extends StrengthEffectQualifier
{
    use EffectTypeTrait;

    /**
     * @var array $valueOrder The order of the values.
     */
    protected $valueOrder = [0, 'type', 'strength'];

    /**
     * GradientFade constructor.
     *
     * @param string|int  $strength
     * @param string|null $type
     */
    public function __construct($strength = null, $type = null)
    {
        parent::__construct(PixelEffect::GRADIENT_FADE, EffectRange::PERCENT);

        $this->strength($strength);
        $this->type($type);
    }
}
