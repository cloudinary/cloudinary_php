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
 * Trait UserVariableBuilderTrait
 *
 * @api
 */
trait UserVariableBuilderTrait
{
    /**
     * Sets the user variable by name.
     *
     * @param string $variableName The user variable name.
     *
     * @return Expression
     */
    public function uVar($variableName)
    {
        return $this->setRightOperand(UVar::uVar($variableName));
    }
}
