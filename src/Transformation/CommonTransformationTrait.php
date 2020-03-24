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
use Cloudinary\Transformation\Expression\BaseExpressionComponent;
use Cloudinary\Transformation\Parameter\GenericParameter;
use Cloudinary\Transformation\Variable\Variable;

/**
 * Trait TransformationTrait
 *
 * @api
 */
trait CommonTransformationTrait
{
    use TransformationResizeTrait;
    use CommonTransformationFlagTrait;
    use TransformationCustomFunctionTrait;

    /**
     * Forces format conversion to the given format.
     *
     * (Formerly known as fetch format)
     *
     * @param Format|string $format
     *
     * @return static
     */
    public function format($format)
    {
        return $this->addAction(ClassUtils::verifyInstance($format, Format::class));
    }

    /**
     * Controls compression quality. 1 is the lowest quality and 100 is the
     * highest.
     *
     * Reducing the quality is a trade-off between visual quality and file size.
     *
     * @param int|string|Quality $quality
     *
     * @return static
     */
    public function quality($quality)
    {
        return $this->addAction(ClassUtils::verifyInstance($quality, Quality::class));
    }

    /**
     * Applies a filter or an effect on an asset.
     *
     * @param EffectParam|EffectAction $effect
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
     * @param EffectParam|EffectAction $adjustment
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
     * @param string $transformationName
     *
     * @return static
     */
    public function namedTransformation($transformationName)
    {
        return $this->addAction(ClassUtils::verifyInstance($transformationName, NamedTransformation::class));
    }

    /**
     * Adds a generic parameter as a separate action.
     *
     * @param string      $shortName The generic parameter name.
     * @param array|mixed $value     The generic parameter value.
     *
     * @return static
     */
    public function addGenericParam($shortName, ...$value)
    {
        return $this->addAction(new GenericParameter($shortName, ...$value));
    }

    /**
     * Adds action defined as an array of parameters.
     *
     * @param array $parameters An associative array of parameters
     *
     * @return static
     *
     * @see ParametersAction
     */
    public function addActionFromParams($parameters)
    {
        return $this->addAction(new ParametersAction($parameters));
    }

    /**
     * Defines an new user variable.
     *
     * @param string $name  The variable name
     * @param mixed  $value The variable value
     *
     * @return static
     */
    public function variable($name, $value)
    {
        return $this->addAction(new Variable($name, $value));
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
        return $this->addAction(Rotate::angle($angle));
    }

    /**
     * Specifies a condition to be met before applying a transformation.
     *
     * @see https://cloudinary.com/documentation/conditional_transformations
     *
     * @param BaseExpressionComponent|string $expression The conditional expression
     *
     * @return static
     */
    public function ifCondition($expression)
    {
        return $this->addAction(new IfCondition($expression));
    }

    /**
     * Specifies a transformation that is applied in the case that the initial condition is evaluated as false.
     *
     * @see https://cloudinary.com/documentation/conditional_transformations
     *
     * @return static
     */
    public function ifElse()
    {
        return $this->addAction(new IfElse());
    }

    /**
     * Finishes the conditional transformation.
     *
     * @see https://cloudinary.com/documentation/conditional_transformations
     *
     * @return static
     */
    public function endIfCondition()
    {
        return $this->addAction(new EndIfCondition());
    }

    /**
     * Applies the 3D LUT file to the asset.
     *
     * @see https://cloudinary.com/documentation/image_transformations#applying_3d_luts_to_images
     *
     * @param string $lutId The 3D LUT file id
     *
     * @return static
     */
    public function add3DLut($lutId)
    {
        return $this->addAction(ClassUtils::verifyInstance($lutId, LutLayer::class));
    }
}
