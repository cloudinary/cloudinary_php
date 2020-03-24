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
 * Class ExpressionOperator
 *
 * @api
 */
class ExpressionOperator extends BaseExpression
{
    use PredefinedVariableBuilderTrait;
    use UserVariableBuilderTrait;
    use ValueBuilderTrait;
    use ExpressionTrait;
}
