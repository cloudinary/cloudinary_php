<?php

namespace Cloudinary\Metadata\Validators;

use Cloudinary\Utils;

/**
 * Class DateGreaterThan
 *
 * Great-than rule for dates.
 *
 * @package Cloudinary\Metadata\Validators
 */
class DateGreaterThan extends ComparisonRule
{
    /**
     * Create a new rule with the given integer.
     *
     * @param \DateTime $value The date to reference in the rule.
     * @param bool $equals Whether a field value equal to the rule value is considered valid.
     */
    public function __construct($value, $equals = false)
    {
        parent::__construct(self::GREATER_THAN, $value, $equals);
    }

    /**
     * @param \DateTime $value
     */
    protected function setValue($value)
    {
        $this->value = Utils::toISO8601DateOnly($value);
    }
}
