<?php

namespace Cloudinary\Metadata\Validators;

use Cloudinary\Utils;

/**
 * Class DateLessThan
 *
 * Less-than rule for dates.
 *
 * @package Cloudinary\Metadata\Validators
 */
class DateLessThan extends ComparisonRule
{
    /**
     * Create a new rule with the given date.
     *
     * @param \DateTime $value The date to reference in the rule.
     * @param bool $equals Whether a field value equal to the rule value is considered valid.
     */
    public function __construct($value, $equals = false)
    {
        parent::__construct(self::LESS_THAN, $value, $equals);
    }

    /**
     * @param \DateTime $value
     */
    protected function setValue($value)
    {
        $this->value = Utils::toISO8601DateOnly($value);
    }
}
