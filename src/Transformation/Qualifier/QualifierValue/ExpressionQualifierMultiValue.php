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

use Cloudinary\Transformation\Expression\ExpressionUtils;
use Cloudinary\Transformation\Qualifier\BaseQualifier;

/**
 * Class ExpressionQualifierMultiValue
 *
 * This class represents a complex value expression of the cloudinary transformation qualifier.
 *
 * @used-by BaseQualifier
 */
class ExpressionQualifierMultiValue extends QualifierMultiValue
{
    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        return ExpressionUtils::normalize(parent::__toString());
    }
}
