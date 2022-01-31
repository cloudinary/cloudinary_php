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
 * Class Cartoonify
 *
 * @see \Cloudinary\Transformation\MiscEffectTrait::cartoonify()
 */
class Cartoonify extends LimitedEffectQualifier
{
    /**
     * @var array $valueOrder The order of the values.
     */
    protected $valueOrder = [0, 'line_strength', 'color_reduction'];

    /**
     * Use for $colorReduction to achieve a black and white cartoon effect.
     */
    const BLACK_WHITE = 'bw';

    /**
     * Trim constructor.
     *
     * @param null  $lineStrength
     * @param null  $colorReduction
     * @param array $args
     */
    public function __construct($lineStrength = null, $colorReduction = null, ...$args)
    {
        parent::__construct(MiscEffect::CARTOONIFY, EffectRange::PERCENT, ...$args);

        $this->lineStrength($lineStrength);
        $this->colorReductionLevel($colorReduction);
    }

    /**
     * Sets the thickness of the lines.
     *
     * @param float $lineStrength The thickness of the lines. (Range: 0 to 100, Server default: 50)
     *
     * @return Cartoonify
     */
    public function lineStrength($lineStrength)
    {
        $this->value->setSimpleValue('line_strength', $lineStrength);

        return $this;
    }

    /**
     * Sets the decrease in the number of colors and corresponding saturation boost of the remaining colors.
     *
     * @param float|string $colorReduction The decrease in the number of colors and corresponding saturation boost of
     *                                     the remaining colors. (Range: 0 to 100). Higher reduction values
     *                                     result in a less realistic look. Set $colorReduction to
     *                                     Cartoonify::BLACK_WHITE for a black and white cartoon effect.
     *
     * @return Cartoonify
     */
    public function colorReductionLevel($colorReduction)
    {
        $this->value->setSimpleValue('color_reduction', $colorReduction);

        return $this;
    }

    /**
     * Creates a black and white cartoon effect.
     *
     * @return Cartoonify
     */
    public function blackWhite()
    {
        return $this->colorReductionLevel(self::BLACK_WHITE);
    }
}
