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

/**
 * Class Condition
 *
 * Represents conditional transformation.
 *
 * @api
 */
class Conditional extends CommonTransformation
{
    /**
     * Condition named constructor.
     *
     * @param BaseExpressionComponent|string $expression
     *
     * @param BaseAction|Transformation $action
     *
     * @return Conditional
     */
    public static function ifCondition($expression, $action)
    {
        $ct = new static();

        $ct->setIfCondition(new IfCondition($expression));
        $ct->addAction($action);

        return $ct;
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
    public function setIfCondition($expression)
    {
        return $this->addAction(new IfCondition($expression));
    }

    /**
     * Specifies a transformation that is applied in the case that the initial condition is evaluated as false.
     *
     * @see https://cloudinary.com/documentation/conditional_transformations
     *
     * @param Action|Transformation $action
     *
     * @return static
     */
    public function otherwise($action)
    {
        return $this->addAction(new IfElse())->addAction($action);
    }

    /**
     * Finishes the conditional transformation.
     *
     * @see https://cloudinary.com/documentation/conditional_transformations
     *
     * @return static
     */
    protected function endIfCondition()
    {
        return $this->addAction(new EndIfCondition());
    }

    /**
     * Serializes transformation to URL.
     *
     * @param ImageTransformation|string|null $withTransformation Optional transformation to append.
     *
     * @return string
     */
    public function toUrl($withTransformation = null)
    {
        $t = new Transformation();

        $t->addAction(new EndIfCondition());
        $t->addTransformation($withTransformation);

        return parent::toUrl($t);
    }
}
