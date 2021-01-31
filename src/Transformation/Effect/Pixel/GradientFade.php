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
 * Class GradientFade
 *
 * Applies a gradient fade effect from one edge of the image.
 *
 * Use ->horizontalStartPoint() for x or ->verticalStartPoint() for y to indicate from which edge to fade and how much
 * of the image should be faded. Values of x and y can be specified as a percentage (Range: 0.0 to 1.0), or in pixels
 * (integer values). Positive values fade from the top (y) or left (x). Negative values fade from the bottom (y) or
 * right (x). By default, the gradient is applied to the top 50% of the image (y = 0.5). Only one direction can be
 * specified but the fade can be applied symmetrically using the mode qualifier. To apply different amounts of fade to
 * multiple edges, use chained fade effects.
 *
 * @see ImagePixelEffectTrait::gradientFade()
 *
 * @api
 */
class GradientFade extends StrengthEffectAction
{
    use EffectActionTypeTrait;

    /**
     * Instructs the gradient fade to be applied symmetrically (to opposite edges of the image).
     */
    const SYMMETRIC = 'symmetric';

    /**
     * Instructs the gradient fade to be applied symmetrically (to opposite edges of the image) including
     * background padding.
     */
    const SYMMETRIC_PAD = 'symmetric_pad';

    /**
     * GradientFade constructor.
     *
     * @param      $strength
     * @param null $type
     */
    public function __construct($strength = null, $type = null)
    {
        parent::__construct(new GradientFadeQualifier($strength, $type));
    }

    /**
     * Instructs the gradient fade to be applied symmetrically (to opposite edges of the image).
     *
     * @return string
     */
    public static function symmetric()
    {
        return self::SYMMETRIC;
    }

    /**
     * Instructs the gradient fade to be applied symmetrically (to opposite edges of the image) including
     * background padding.
     *
     * @return string
     */
    public static function symmetricPad()
    {
        return self::SYMMETRIC_PAD;
    }

    /**
     * Sets the horizontal start point (x).
     *
     * @param int|float|string $x The value of the x dimension.
     *
     * @return $this
     */
    public function horizontalStartPoint($x)
    {
        return $this->setPointValue(ClassUtils::verifyInstance($x, X::class));
    }

    /**
     * Sets the vertical start point (y).
     *
     * @param int|float|string $y The value of the y dimension.
     *
     * @return $this
     */
    public function verticalStartPoint($y)
    {
        return $this->setPointValue(ClassUtils::verifyInstance($y, Y::class));
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
    protected function setPointValue($value)
    {
        if (! isset($this->qualifiers[Point::getName()])) {
            $this->addQualifier(new Point());
        }

        $this->qualifiers[Point::getName()]->setPointValue($value);

        return $this;
    }
}
