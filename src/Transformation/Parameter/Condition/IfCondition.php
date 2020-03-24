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
use Cloudinary\Transformation\Expression\Expression;

/**
 * Class IfCondition
 */
class IfCondition extends BaseParameter
{
    /**
     * @var string $key Serialization key.
     */
    protected static $key = 'if';

    /**
     * IfCondition constructor.
     *
     * @param BaseExpressionComponent|string $expression
     */
    public function __construct($expression)
    {
        if (is_string($expression)) {
            $expression = Expression::raw($expression);
        }

        parent::__construct($expression);
    }
}
