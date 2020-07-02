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
 * Class GradientFade
 *
 * Applies a gradient fade effect from one edge of the image.
 *
 * Use ->x() or ->y() to indicate from which edge to fade and how much of the image should be faded.

 * Values of x and y can be specified as a percentage (Range: 0.0 to 1.0),
 * or in pixels (integer values). Positive values fade from the top (y) or left (x).
 * Negative values fade from the bottom (y) or right (x).
 * By default, the gradient is applied to the top 50% of the image (y = 0.5).
 * Only one direction can be specified but the fade can be applied symmetrically using the mode parameter.
 * To apply different amounts of fade to multiple edges, use chained fade effects.
 *
 * @see ImagePixelEffectTrait::gradientFade()
 *
 * @api
 */
class GradientFade extends EffectAction
{
    use PointTrait;

    /**
     * Instructs the gradient fade to be applied symmetrically (to opposite edges of the image).
     */
    const SYMMETRIC     = 'symmetric';

    /**
     * Instructs the gradient fade to be applied symmetrically (to opposite edges of the image) including
     * background padding.
     */
    const SYMMETRIC_PAD = 'symmetric_pad';

    /**
     * GradientFade constructor.
     *
     * @param      $strength
     * @param null $mode
     */
    public function __construct($strength = null, $mode = null)
    {
        parent::__construct(new GradientFadeParam($strength, $mode));
    }

    /**
     * Internal setter for the point value.
     *
     * @param mixed $value
     *
     * @return static
     *
     * @internal
     */
    public function setPointValue($value)
    {
        if (! isset($this->parameters[Point::getName()])) {
            $this->addParameter(new Point());
        }

        $this->parameters[Point::getName()]->setPointValue($value);

        return $this;
    }
}
