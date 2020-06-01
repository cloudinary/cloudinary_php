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

use Cloudinary\Transformation\Expression\BaseExpressionComponent;
use Cloudinary\Transformation\Parameter\BaseParameter;

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
     * @param EffectParam|EffectAction $effect The effect to apply.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\EffectAction
     * @see \Cloudinary\Transformation\EffectParam
     */
    public function effect($effect);

    /**
     * Applies an adjustment effect on an asset.
     *
     * @param EffectParam|EffectAction $adjustment The adjustment effect to apply.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\EffectAction
     * @see \Cloudinary\Transformation\EffectParam
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
     * Adds a generic parameter as a separate action.
     *
     * @param string      $shortName The generic parameter name.
     * @param array|mixed $value     The generic parameter value.
     *
     * @return static
     */
    public function addGenericParam($shortName, ...$value);

    /**
     * Adds (chains) a transformation action.
     *
     * @param BaseAction|BaseParameter|mixed $action The transformation action to add.
     *                                               If BaseParameter is provided, it is wrapped with action.
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
     * Adds action defined as an array of parameters.
     *
     * @param array $parameters An associative array of parameters
     *
     * @return static
     *
     * @see ParametersAction
     */
    public function addActionFromParams($parameters);

    /**
     * Defines a new user variable.
     *
     * @param string $name  The variable name
     * @param mixed  $value The variable value
     *
     * @return static
     */
    public function variable($name, $value);

    /**
     * Rotates the asset by the given angle.
     *
     * @param string|int $angle The rotation angle.
     *
     * @return static
     */
    public function rotate($angle);

    /**
     * Specifies a condition to be met before applying a transformation.
     *
     * @param BaseExpressionComponent|string $expression The conditional expression
     *
     * @return static
     *
     * @see https://cloudinary.com/documentation/conditional_transformations
     */
    public function ifCondition($expression);

    /**
     * Specifies a transformation that is applied in the case that the initial condition is evaluated as false.
     *
     * @return static
     *
     * @see https://cloudinary.com/documentation/conditional_transformations
     */
    public function ifElse();

    /**
     * Finishes the conditional transformation.
     *
     * @return static
     *
     * @see https://cloudinary.com/documentation/conditional_transformations
     */
    public function endIfCondition();

    /**
     * Applies the 3D look-up table (LUT) file to the asset.
     *
     * @param string $lutId The 3D LUT file id
     *
     * @return static
     *
     * @see https://cloudinary.com/documentation/image_transformations#applying_3d_luts_to_images
     */
    public function add3DLut($lutId);
}
