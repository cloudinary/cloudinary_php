<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Expression;

/**
 * Trait StringRelationalOperatorsTrait
 *
 * @api
 */
trait StringRelationalOperatorBuilderTrait
{
    /**
     * Is in (a list of strings).
     *
     * @return ExpressionOperator
     */
    public function in()
    {
        return $this->buildExpression(StringRelationalOperator::in());
    }

    /**
     * Is not in (a list of strings).
     *
     * @return ExpressionOperator
     */
    public function notIn()
    {
        return $this->buildExpression(StringRelationalOperator::notIn());
    }
}
