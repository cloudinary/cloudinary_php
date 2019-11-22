<?php

namespace Cloudinary\Metadata\Validators;

/**
 * Class IntLessThan
 *
 * Less-than rule for integers.
 *
 * @package Cloudinary\Metadata\Validators
 */
class IntLessThan extends ComparisonRule
{
    /**
     * Create a new rule with the given integer.
     *
     * @param int $value The integer to reference in the rule.
     * @param bool $equals Whether a field value equal to the rule value is considered valid.
     */
    public function __construct($value, $equals = false)
    {
        parent::__construct(self::LESS_THAN, $value, $equals);
    }
}
