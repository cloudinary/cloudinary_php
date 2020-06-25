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

use Cloudinary\Transformation\Parameter\GenericParameter;

/**
 * Defines how to apply a particular transformation.
 *
 * @api
 */
abstract class Parameter
{
    use ResizeParamTrait;
    use DimensionsParamTrait;
    use PositioningParamTrait;
    use EffectParamTrait;
    use LayerParamTrait;
    use AngleParamTrait;
    use ColorParamTrait;
    use BackgroundParamTrait;
    use BorderParamTrait;
    use FlagParamTrait;
    use QualityParamTrait;
    use OpacityParamTrait;
    use CornerRadiusParamTrait;
    use VideoRangeParamTrait;
    use ConditionParamTrait;
    use AudioParamTrait;
    use VideoParamTrait;
    use PageParamTrait;
    use FormatParamTrait;
    use ColorSpaceParamTrait;
    use ImageParamTrait;
    use GravityParamTrait;
    use NamedTransformationParamTrait;
    use CustomFunctionParamTrait;

    /**
     * Sets any parameter to any value.
     *
     * For future compatibility.
     *
     * @param string $genericKey The name of any parameter.
     * @param mixed  ...$value   The value of the parameter.
     *
     * @return GenericParameter
     */
    public static function generic($genericKey, ...$value)
    {
        return new GenericParameter($genericKey, ...$value);
    }
}
