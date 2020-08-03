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
 * Greater-than rule for dates.
 *
 * @api
 */
class DateGreaterThan extends ComparisonRule
{
    /**
     * Create a new rule with the given integer.
     *
     * @param DateTime $value  The date to reference in the rule.
     * @param bool     $equals Whether a field value equal to the rule value is considered valid.
     */
    public function __construct($value, $equals = false)
    {
        parent::__construct(self::GREATER_THAN, $value, $equals);
    }

    /**
     * @param DateTime $value
     */
    protected function setValue($value)
    {
        $this->value = Utils::toISO8601DateOnly($value);
    }
}
