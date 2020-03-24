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
 * Class GradientFadeParam
 */
class GradientFadeParam extends LimitedEffectParam
{
    use EffectModeTrait;

    /**
     * @var array $valueOrder The order of the values.
     */
    protected $valueOrder = [0, 'mode', 'value'];

    /**
     * GradientFade constructor.
     *
     * @param string|int  $strength
     * @param string|null $mode
     */
    public function __construct($strength = null, $mode = null)
    {
        parent::__construct(PixelEffect::GRADIENT_FADE, EffectRange::PERCENT, $strength);

        $this->mode($mode);
    }
}
