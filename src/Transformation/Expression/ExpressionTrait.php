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
 * Trait ExpressionTrait
 *
 * @api
 */
trait ExpressionTrait
{
    /**
     * Sets the expression value.
     *
     * @param mixed $value The expression value.
     *
     * @return self
     */
    public function expression($value)
    {
        return $this->setRightOperand($value);
    }
}
