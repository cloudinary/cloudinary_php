<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Qualifier;

use Cloudinary\Transformation\ExpressionQualifierMultiValue;

/**
 * Class BaseExpressionQualifier
 */
abstract class BaseExpressionQualifier extends BaseQualifier
{
    /**
     * @var string VALUE_CLASS The class of the qualifier value. Can be customized by derived classes.
     */
    const VALUE_CLASS = ExpressionQualifierMultiValue::class;
}
