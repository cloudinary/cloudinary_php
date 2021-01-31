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

use Cloudinary\Transformation\Qualifier\GenericQualifier;

/**
 * Defines how to apply a particular transformation.
 *
 * @api
 */
abstract class Qualifier
{
    use ResizeQualifierTrait;
    use DimensionsQualifierTrait;
    use PositioningQualifierTrait;
    use EffectQualifierTrait;
    use LayerQualifierTrait;
    use AngleQualifierTrait;
    use ColorQualifierTrait;
    use BackgroundQualifierTrait;
    use BorderQualifierTrait;
    use FlagQualifierTrait;
    use QualityQualifierTrait;
    use OpacityQualifierTrait;
    use CornerRadiusQualifierTrait;
    use TimelineQualifierTrait;
    use ConditionQualifierTrait;
    use AudioQualifierTrait;
    use VideoQualifierTrait;
    use PageQualifierTrait;
    use FormatQualifierTrait;
    use ColorSpaceQualifierTrait;
    use ImageQualifierTrait;
    use GravityQualifierTrait;
    use NamedTransformationQualifierTrait;
    use CustomFunctionQualifierTrait;

    /**
     * Sets any qualifier to any value.
     *
     * For future compatibility.
     *
     * @param string $genericKey The name of any qualifier.
     * @param mixed  ...$value   The value of the qualifier.
     *
     * @return GenericQualifier
     */
    public static function generic($genericKey, ...$value)
    {
        return new GenericQualifier($genericKey, ...$value);
    }
}
