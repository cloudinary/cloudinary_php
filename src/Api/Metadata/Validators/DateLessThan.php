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

use Cloudinary\Utils;
use DateTime;

/**
 * Less-than rule for dates.
 *
 * @api
 */
class DateLessThan extends ComparisonRule
{
    /**
     * Create a new rule with the given date.
     *
     * @param DateTime $value  The date to reference in the rule.
     * @param bool     $equals Whether a field value equal to the rule value is considered valid.
     */
    public function __construct($value, $equals = false)
    {
        parent::__construct(self::LESS_THAN, $value, $equals);
    }

    protected function setValue(mixed $value): void
    {
        $this->value = Utils::toISO8601DateOnly($value);
    }
}
