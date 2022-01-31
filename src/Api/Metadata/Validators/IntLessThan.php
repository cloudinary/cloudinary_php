<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Api\Metadata\Validators;

/**
 * Less-than rule for integers.
 *
 * @api
 */
class IntLessThan extends ComparisonRule
{
    /**
     * Create a new rule with the given integer.
     *
     * @param int  $value  The integer to reference in the rule.
     * @param bool $equals Whether a field value equal to the rule value is considered valid.
     */
    public function __construct($value, $equals = false)
    {
        parent::__construct(self::LESS_THAN, $value, $equals);
    }
}
