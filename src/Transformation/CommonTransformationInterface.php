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

use Cloudinary\Transformation\Qualifier\BaseQualifier;

/**
 * Interface CommonTransformationInterface
 */
interface CommonTransformationInterface extends ComponentInterface
{
    /**
     * Forces format conversion to the given format.
     *
     * (Formerly known as fetch format).
     *
     * @param Format|string $format The format in which to deliver the asset.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\Format
     */
    public function format($format);

    /**
     * Controls compression quality.
     *
     * Reducing the quality is a trade-off between visual quality and file size.
     *
     * @param int|string|Quality $quality The quality value. (Range 1 to 100)
     *
     * @return static
     */
    public function quality($quality);

    /**
     * Applies a filter or an effect on an asset.
     *
     * @param EffectQualifier|EffectAction $effect The effect to apply.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\EffectAction
     * @see \Cloudinary\Transformation\EffectQualifier
     */
    public function effect($effect);

    /**
     * Applies an adjustment effect on an asset.
     *
     * @param EffectQualifier|EffectAction $adjustment The adjustment effect to apply.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\EffectAction
     * @see \Cloudinary\Transformation\EffectQualifier
     */
    public function adjust($adjustment);

    /**
     * Applies a pre-defined transformation of the given name.
     *
     * @param string $transformationName The name of the transformation.
     *
     * @return static
     */
    public function namedTransformation($transformationName);

    /**
     * Adds a generic qualifier as a separate action.
     *
     * @param string      $shortName The generic qualifier name.
     * @param array|mixed $value     The generic qualifier value.
     *
     * @return static
     */
    public function addGenericQualifier($shortName, ...$value);

    /**
     * Adds (chains) a transformation action.
     *
     * @param BaseAction|BaseQualifier|mixed $action The transformation action to add.
     *                                               If BaseQualifier is provided, it is wrapped with action.
     *
     * @return static
     */
    public function addAction($action);

    /**
     * Adds (appends) a transformation.
     *
     * Appended transformation is nested.
     *
     * @param CommonTransformation $transformation The transformation to add.
     *
     * @return static
     */
    public function addTransformation($transformation);

    /**
     * Adds action defined as an array of qualifiers.
     *
     * @param array $qualifiers An associative array of qualifiers
     *
     * @return static
     *
     * @see QualifiersAction
     */
    public function addActionFromQualifiers($qualifiers);

    /**
     * Defines a new user variable.
     *
     * @param string $name  The variable name
     * @param mixed  $value The variable value
     *
     * @return static
     */
    public function addVariable($name, $value);

    /**
     * Rotates the asset by the given angle.
     *
     * @param string|int $angle The rotation angle.
     *
     * @return static
     */
    public function rotate($angle);

    /**
     * Specifies a conditional transformation whose condition should be met before applying a transformation.
     *
     * @param Conditional $conditionalTransformation The conditional transformation.
     *
     * @return static
     *
     * @see https://cloudinary.com/documentation/conditional_transformations
     */
    public function conditional($conditionalTransformation);
}
