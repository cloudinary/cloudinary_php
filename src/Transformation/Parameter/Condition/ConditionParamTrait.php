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
 * Trait ConditionParamTrait
 *
 * @api
 */
trait ConditionParamTrait
{
    /**
     * Sets up a conditional transformation.
     *
     * For examples of conditional transformations see
     * {@see https://cloudinary.com/documentation/conditional_transformations Conditional transformations}.
     *
     * @param string $expression The condition to meet in order to apply the transformation.
     *
     * @return IfCondition
     *
     * @see \Cloudinary\Transformation\IfCondition
     */
    public static function ifCondition($expression)
    {
        return new IfCondition($expression);
    }
}
