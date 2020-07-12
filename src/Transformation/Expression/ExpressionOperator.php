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
 * Defines the supported image characteristics for conditional transformations.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/conditional_transformations#supported_image_characteristics" target="_blank">
 * Supported image characteristics</a>
 *
 *
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
