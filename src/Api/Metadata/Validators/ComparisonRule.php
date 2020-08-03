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
 * Base class for all comparison (greater than/less than) validation rules.
 *
 * @api
 */
abstract class ComparisonRule extends MetadataValidation
{
    const GREATER_THAN = 'greater_than';
    const LESS_THAN    = 'less_than';

    /**
     * ComparisonRule constructor.
     *
     * @param string $type
     * @param mixed  $value
     * @param bool   $equals
     */
    public function __construct($type, $value, $equals = false)
    {
        $this->type = $type;
        $this->setValue($value);
        $this->equals = $equals;
    }

    /**
     * @param mixed $value
     */
    protected function setValue($value)
    {
        $this->value = $value;
    }
}
