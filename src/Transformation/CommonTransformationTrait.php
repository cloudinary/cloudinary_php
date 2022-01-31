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
use Cloudinary\Transformation\Qualifier\GenericQualifier;
use Cloudinary\Transformation\Variable\Variable;

/**
 * Trait TransformationTrait
 *
 * @api
 */
trait CommonTransformationTrait
{
    use TransformationResizeTrait;
    use TransformationDeliveryTrait;
    use CommonTransformationFlagTrait;
    use TransformationCustomFunctionTrait;

    /**
     * Applies a filter or an effect on an asset.
     *
     * @param EffectQualifier|EffectAction $effect
     *
     * @return static
     */
    public function effect($effect)
    {
        return $this->addAction($effect);
    }

    /**
     * Applies adjustment effect on an asset.
     *
     * @param EffectQualifier|EffectAction|AdjustmentInterface $adjustment
     *
     * @return static
     */
    public function adjust($adjustment)
    {
        return $this->addAction($adjustment);
    }

    /**
     * Applies a pre-defined named transformation of the given name.
     *
     * @param string|NamedTransformation $transformationName
     *
     * @return static
     */
    public function namedTransformation($transformationName)
    {
        return $this->addAction(ClassUtils::verifyInstance($transformationName, NamedTransformation::class));
    }

    /**
     * Adds a generic qualifier as a separate action.
     *
     * @param string      $shortName The generic qualifier name.
     * @param array|mixed $value     The generic qualifier value.
     *
     * @return static
     */
    public function addGenericQualifier($shortName, ...$value)
    {
        return $this->addAction(new GenericQualifier($shortName, ...$value));
    }

    /**
     * Adds action defined as an array of qualifiers.
     *
     * @param array $qualifiers An associative array of qualifiers
     *
     * @return static
     *
     * @see QualifiersAction
     */
    public function addActionFromQualifiers($qualifiers)
    {
        return $this->addAction(new QualifiersAction($qualifiers));
    }

    /**
     * Adds a flag as a separate action.
     *
     * @param FlagQualifier|string $flag The flag to add.
     *
     * @return static
     */
    public function addFlag($flag)
    {
        return $this->addAction(ClassUtils::verifyInstance($flag, FlagQualifier::class));
    }

    /**
     * Defines an new user variable.
     *
     * @param string|Variable $name  The variable name or the Variable instance.
     * @param mixed           $value The variable value.
     *
     * @return static
     */
    public function addVariable($name, $value = null)
    {
        return $this->addAction(ClassUtils::verifyInstance($name, Variable::class, null, $value));
    }

    /**
     * Rotates the asset by the given angle.
     *
     * @param string|int $angle The rotation angle.
     *
     * @return static
     */
    public function rotate($angle)
    {
        return $this->addAction(ClassUtils::verifyInstance($angle, Rotate::class));
    }

    /**
     * Specifies a conditional transformation whose condition should be met before applying a transformation.
     *
     * @param Conditional $conditionalTransformation The conditional transformation.
     *
     * @return static
     *
     * @see https://cloudinary.com/documentation/conditional_transformations
     */
    public function conditional($conditionalTransformation)
    {
        return $this->addTransformation(ClassUtils::verifyInstance($conditionalTransformation, Conditional::class));
    }
}
